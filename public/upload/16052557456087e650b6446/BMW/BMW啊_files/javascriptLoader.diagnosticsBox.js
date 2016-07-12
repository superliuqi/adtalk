/**
 * -----------------------------------------------------------------------------
 *
 * DiagnosticsBox shows diagnostics... in a box. (Obvious comment is obvious!)
 *
 * -----------------------------------------------------------------------------
 */
var rlDiagnosticsBox = function() {

  // jQuery DOM elements
  var _statusDisplay = null;
  var _statusTable = null;
  var _statusDiagCount = null;

  // Current state
  var _isUserLoggedIn = false;
  var _runningDiags = 0;
  var _unsuccessfulDiags = 0;

  // timeout per call
  var TIMEOUT = 3000;

  // Diagnostic states
  var STATE_BLOCKED = 0,    // Waiting for pre-requisites to finish. Transient state.
    STATE_SCHEDULED = 1,  // Ready for processing. Transient state.
    STATE_RUNNING = 2,    // Currently being processed. Transient state.
    STATE_SUCCESS = 3,    // Completed successfully. Final state.
    STATE_FAILED = 4,     // Completed with errors. Final state.
    STATE_ABORTED = 5,    // Pre-requisites not met, aborted. Final state.
    STATE_SKIPPED = 6,    // No need to run this diagnostic. Final state.
    STATE_WARN = 7;       // Completed with warnings. Final state.

  var items = {};
  items[STATE_SCHEDULED] = { label: "Scheduled", color: "#777777" };
  items[STATE_RUNNING] = { label: "Running", color: "#777777" };
  items[STATE_BLOCKED] = { label: "Blocked", color: "#777777" };
  items[STATE_SUCCESS] = { label: "OK", color: "green" };
  items[STATE_FAILED] = { label: "<b>Fail</b>", color: "red" };
  items[STATE_ABORTED] = { label: "Aborted", color: "red" };
  items[STATE_SKIPPED] = { label: "n/a", color: "#777777" };
  items[STATE_WARN]    = { label: "<b>Warn</b>", color: "#FFCC00" };


  // Diagnostics supported by this utility
  var diagnostics = [];

  // Logically grouped diagnostics
  var diagnosticGroups = {
    all: function() {
      var ret = [];
      for (var i=0; i<diagnostics.length; i++) {
        if (diagnostics[i].pingURL) {
          ret.push(diagnostics[i].diagId);
        }
      }
      return ret;
    }
  };

  /* ******************* Initialisation ******************* */

  function initImpl() {
    if (typeof rlPage === "undefined") {
      console.error("Cannot proceed without rlPage object");
      return;
    }

    coreHost = rlPage.getNextCoreUrl();
    diagnostics = [
      /* Simple ping-based diagnostics */
      { diagId:"usermgmt-user.ping", pingURL: coreHost + "/usermgmt-user/services/nomandator/user/ping", requires: ["subsys.core"] },
      { diagId:"usermgmt-meta.ping", pingURL: coreHost + "/usermgmt-metadata/services/nomandator/fielddescription/ping", requires: ["subsys.core"] },
      { diagId:"usermgmt-rfx.ping", pingURL: coreHost + "/usermgmt-rfx/services/nomandator/request/ping", requires: ["subsys.core"]  },
      { diagId:"usermgmt-contact.ping", pingURL: coreHost + "/usermgmt-rfx/services/nomandator/contact/ping", requires: ["subsys.core"]  },
      { diagId:"ncc-permittedValues.ping", pingURL: '/vehicledata/api/v1/pub/readPermittedValues/_{mandatorContext}/ping' },
      { diagId:"ncc-vehicleModel.ping", pingURL: '/vehicledata/api/v1/pub/vehicleModel/_{mandatorContext}/ping' },
      { diagId:"ncc-vehicleModel.brands", pingURL: '/vehicledata/api/v1/pub/vehicleModel/_{mandatorContext}/brands' },
      { diagId:"ncc-povs.ping", pingURL: '/vc/api/v1/me/preOwnedVehicle/_{mandatorContext}/count', requires: ["env.loginState"] , skipWhenOffline: true },
      { diagId:"ncc-listGarage.ping", pingURL: '/vc/api/v1/me/listGarage/_{mandatorContext}/count', requires: ["env.loginState"] , skipWhenOffline: true },
      { diagId:"perso-offer.ping", pingURL: coreHost + "/perso/services/nomandator/offer/ping", requires: ["subsys.core"]  },
      { diagId:"perso-message.ping", pingURL: coreHost + "/perso/services/nomandator/message/ping", requires: ["subsys.core"]  },
      { diagId:"perso-tracking.ping", pingURL: coreHost + "/perso/services/nomandator/tracking/ping", requires: ["subsys.core"]  },

      /* Complex diagnostics */
      { diagId:"subsys.core", impl: diagImpl_subsys_core },
      { diagId: "env.loginState", impl: diagImpl_env_loginState, requires: ["subsys.core", "usermgmt-user.ping"] },

      // metadata check
      { diagId:"metadata.healthcheck",
        pingURL: coreHost + '/internal/healthcheck/rest/integritycheck/metadata/?mandator=_{mandatorContext}',
        infoURL: coreHost + '/internal/healthcheck/integritycheck.html?check=metadata&mandator=_{mandatorContext}',
        timeout: 10000,
        isHealthcheck: true },
      // perso check
      { diagId:"perso-offer.healthcheck",
        pingURL: coreHost + '/internal/healthcheck/rest/integritycheck/perso/?mandator=_{mandatorContext}',
        infoURL: coreHost + '/internal/healthcheck/integritycheck.html?check=perso&mandator=_{mandatorContext}'+
          '&_persoJsonParams=_{persoJsonParams}',
        timeout: 10000,
        isHealthcheck: true },
      // policy check
      { diagId:"policy.healthcheck",
        pingURL: coreHost + '/internal/healthcheck/rest/integritycheck/policy/?check=policy&mandator=_{mandatorContext}',
        infoURL: coreHost + '/internal/healthcheck/integritycheck.html?check=policy&mandator=_{mandatorContext}',
        timeout: 30000,
        isHealthcheck: true }
    ];

    // Prepare diagnostics
    hookPingDiagnostics();
    for (var i=0; i<diagnostics.length; i++) {
      diagnostics[i].history = [];
    }

    // Do we have URL parameters?
    var qp = rlJQuery_queryParams.getQueryParam("diagnostics");
    if (qp === "on") {
      rlCookieController.setCookie({'cookieKey': "diagnosticsBox", 'cookieValue':'on'});
    } else if (qp === "off") {
      rlCookieController.removeCookie('diagnosticsBox');
    }

    // Listen for "dd" shortcut
    initKeyboardShortcut();

    // Let's get going!
    if (rlCookieController.getCookie('diagnosticsBox') !== null || qp === "once") {
      showDiagnosticsBox();
    }
  }

  function showDiagnosticsBox() {
    rlCookieController.setCookie({'cookieKey': "diagnosticsBox", 'cookieValue':'on'});
    if (jq("#servicePingStatusDisplay").length > 0) {
      jq("#servicePingStatusDisplay").show();
    } else {
      setTimeout(function() { rlDiagnosticsBox.addDiagnostic("all"); }, 500); // 500ms gives rlMandator time to load
    }
  }

  function hideDiagnosticsBox() {
    rlCookieController.setCookie({'cookieKey': "diagnosticsBox", 'cookieValue':''});
    jq("#servicePingStatusDisplay").hide();
  }

  function initKeyboardShortcut() {
    var $count = 0;
    jq(window).keypress(function(event) {
      if (jq(event.target).is(":input")) {
        return;
      }
      if (event.which == 100) {
        $count++;
        window.setTimeout(function() {
          $count = 0;
        }, 500);
        if ($count == 2) {
          if (jq("#servicePingStatusDisplay").is(":visible")) {
            hideDiagnosticsBox();
          } else {
            showDiagnosticsBox();
          }
        }
      } else {
        $count = 0;
      }
    });
  }

  function cookieDomain() {
    var hostname = window.location.host.replace(window.location.protocol+"//", "");
    if (hostname.split(".").length > 2) {
      var idx = hostname.indexOf(".");
      return hostname.substring(idx+1);
    } else {
      return hostname;
    }
  }

  function hookPingDiagnostics() {
    for (var i=0; i<diagnostics.length; i++) {
      if (diagnostics[i].pingURL) {
        diagnostics[i].impl = function() {
          diagImpl_PingURL(this);
        };
      }
    }
  }

  /* ******************* Diagnostic implementations ******************* */

  // Convenience method for service pings
  function diagImpl_PingURL(diagnostic) {
    try {
      rlCrossSubDomainAjax.call({
        type: 'GET',
        url: rlMandator.replaceCoreUrl(diagnostic.pingURL),
        success: function(data) {
          var stat = STATE_SUCCESS;
          if (diagnostic.isHealthcheck) {
            if (!data.data) {
              if(console){
                console.log('result for call "'+diagnostic.diagId+'" does not contain expected element "data"');
              }
              stat = STATE_FAILED;
            }
            else if (!data.data.result) {
              if(console){
                console.log('result for call "'+diagnostic.diagId+'"  does not contain expected element "data.result"');
              }
              stat = STATE_FAILED;
            }
            else {
              var result = data.data.result;
              if (!result || result.toLowerCase() !== 'ok') {
                if ( result.toLowerCase() === 'warn' ) {
                  stat = STATE_WARN;
                }
                else {
                  stat = STATE_FAILED;
                }
              }
            }
          }
          setStatus(diagnostic.diagId, stat);
          processQueue();
        },
        error: function(e) {
          setStatus(diagnostic.diagId, STATE_FAILED);
          processQueue();
        },
        timeout: diagnostic.timeout ? diagnostic.timeout : TIMEOUT
      });
    } catch(e) {
      setStatus(diagnostic.diagId, STATE_FAILED, "AJAX exception");
      processQueue();
    }
  }

  function diagImpl_subsys_core() {
    var diagId = this.diagId;
    rlComponentLoader.deferredExec('coreServices', function() {
      rlCoreServices.isCoreAvailable({
        isAvailable: function() {
          setStatus(diagId, STATE_SUCCESS);
          processQueue();

          // Now load version from core system
          rlCrossSubDomainAjax.call({
            type: 'GET',
            url: coreHost + '/core-version-info/version.txt',
            success: function(data) {
              addDiagnosticStatusHistory(diagId, 'Version', data);
            },
            error: function(e) {
              setStatus(diagnostic.diagId, 'Version', 'Unknown version');
            }
          });
        },
        isNotAvailable: function() {
          setStatus(diagId, STATE_FAILED);
          processQueue();
        }
      });
    });
  }

  function diagImpl_env_loginState() {
    var diagId = this.diagId;
    rlComponentLoader.deferredExec('coreServices', function() {
      rlCoreServices.isLoggedIn({
        loggedIn: function() {
          _isUserLoggedIn = true;
          setStatus(diagId, STATE_SUCCESS);
          processQueue();
        },
        loggedOut: function() {
          _isUserLoggedIn = false;
          setStatus(diagId, STATE_SUCCESS);
          processQueue();
        }
      });
    });
  }

  /* ******************* Diagnostics management ******************* */

  // Try to add a diagnostic to the queue
  function addDiagnosticImpl(sID) {
    // Recursively handle arrays of diagnostic IDs
    if (jq.isArray(sID)) {
      for (var i=0; i<sID.length; i++) {
        addDiagnosticImpl(sID[i]);
      }
      return;
    }

    // Convenience ID for running all diagnostics at once
    if (sID === 'all') {
      addDiagnosticImpl(diagnosticGroups.all());
      return;
    }

    // Figure out which diagnostic to actually run
    var diag = getDiagnosticById(sID);
    if (diag) {
      if (typeof diag.status === "undefined") {
        addDiagnosticToQueue(diag);
      }
    } else {
      buildStatusDisplay();
      createServiceTableRow(sID);
      setStatusUnsupported(sID);
    }
  }

  // Add a specific diagnostic to the queue
  function addDiagnosticToQueue(diagnostic) {
    // Resolve any pre-requisites for this diagnostic
    var state = STATE_SCHEDULED;
    if (diagnostic.requires && jq.isArray(diagnostic.requires)) {
      for (var i=0; i<diagnostic.requires.length; i++) {
        addDiagnosticHistory(diagnostic.diagId, "Depends on: " + diagnostic.requires[i]);
        addDiagnosticImpl(diagnostic.requires[i]);
        var rd = getDiagnosticById(diagnostic.requires[i]);
        if (typeof rd.status !== "undefined") {
          if (rd.status === STATE_FAILED || rd.status == STATE_WARN || rd.status === STATE_ABORTED) {
            state = STATE_ABORTED;
            break;
          } else if (rd.status !== STATE_SUCCESS) {
            state = STATE_BLOCKED;
            break;
          }
        }
      }
    }

    // Add this diagnostic to the GUI
    buildStatusDisplay();
    createServiceTableRow(diagnostic.diagId);

    // Run the diagnostic
    setStatus(diagnostic.diagId, state);
    setRunningDiags(_runningDiags + 1);
    processQueue();
  }

  // Update diagnostic dependencies and run waiting diagnostics 
  function processQueue() {
    // Update dependencies
    for (var i=0; i<diagnostics.length; i++) {
      var diag = diagnostics[i];
      if (typeof diag.status === "undefined" || typeof diag.requires === "undefined")
        continue;
      if (diag.status !== STATE_BLOCKED)
        continue;

      var canSchedule = true;
      for (var j=0; j<diag.requires.length; j++) {
        var reqDiag = getDiagnosticById(diag.requires[j]);
        var rs = reqDiag.status;
        if (rs === STATE_BLOCKED || rs === STATE_RUNNING) {
          canSchedule = false;
          break;
        } else if (rs === STATE_ABORTED || rs === STATE_FAILED || rs === STATE_WARN) {
          canSchedule = false;
          setStatus(diag.diagId, STATE_ABORTED, "Required diagnostic '" + reqDiag.diagId + "' failed");
          break;
        }
      }
      if (canSchedule) {
        setStatus(diag.diagId, STATE_SCHEDULED);
      }
    }

    // Check for diagnostics that are ready to run
    for (var i=0; i<diagnostics.length; i++) {
      var diag = diagnostics[i];
      if (diag.status && diag.status === STATE_SCHEDULED) {
        if (diag.skipWhenOffline && !_isUserLoggedIn) {
          setStatus(diag.diagId, STATE_SKIPPED, "User isn't logged in");
        } else {
          setStatus(diag.diagId, STATE_RUNNING);
          diag.impl();
        }
      }
    }
  }

  function getDiagnosticById(sID) {
    for (var i=0; i<diagnostics.length; i++) {
      if (diagnostics[i].diagId === sID) {
        return diagnostics[i];
      }
    }
  }

  function addDiagnosticHistory(diagId, text) {
    if (typeof text !== "undefined") {
      var h = getDiagnosticById(diagId).history;
      h.push((h.length+1) + ": " + text);
    }
  }

  function addDiagnosticStatusHistory(diagId, statusText, reason) {
    var msg = "Status: " + statusText;
    if (typeof reason !== "undefined") {
      msg += " (" + reason + ")";
    }
    addDiagnosticHistory(diagId, msg);
  }

  function setStatus(sID, status, reason) {

    var item = items[status];
    if (getDiagnosticById(sID).status !== status) {
      jq("#Status_" + getDomIdForDiagnosticId(sID) + " > td.status").html(item.label).css("color", item.color);
      getDiagnosticById(sID).status = status;
      addDiagnosticStatusHistory(sID, item.label, reason);
    }

    if (status === STATE_FAILED || status === STATE_WARN || status === STATE_ABORTED) {
      _unsuccessfulDiags++;
    }

    if (status === STATE_SUCCESS || status === STATE_FAILED || status === STATE_WARN || status === STATE_ABORTED || status === STATE_SKIPPED) {
      setRunningDiags(_runningDiags - 1);
    }
  }

  function setStatusUnsupported(sID) {
    _unsuccessfulDiags++;
    jq("#Status_" + getDomIdForDiagnosticId(sID) + " > td.status").text("Unsupported").css("color", "red");
    setRunningDiags(_runningDiags - 1);
  }

  /* ******************* GUI methods ******************* */

  function getDomIdForDiagnosticId(sID) {
    return sID.replace(".", "_").replace("-", "_");
  }

  function buildStatusDisplay() {
    if (_statusDisplay !== null) {
      return;
    }

    // Diagnostics box & tab container
    jq("body").append("<div id='servicePingStatusDisplay'></div>");
    _statusDisplay = jq("#servicePingStatusDisplay");
    _statusDisplay.css("position", "fixed");
    _statusDisplay.css("bottom", "25px");
    _statusDisplay.css("right", "25px");
    _statusDisplay.css("max-width", "250px");
    _statusDisplay.css("padding", "8px");
    _statusDisplay.css("border", "1px solid #cccccc");
    _statusDisplay.css("background", "#eeeeee");
    _statusDisplay.css("z-index", "500000");

    // Headline
    jq("<div></div>")
      .text("Diagnostics Box")
      .css({ "font-weight": "bold", "text-align": "center" })
      .appendTo(_statusDisplay);

    // Tab headers
    _statusDisplay.append("<ul id='dboxTabs'></ul>");
    var tabContainer = jq("#dboxTabs");
    tabContainer.append("<li><a href='#dboxTabDiagnostics'>Diag</a></li>");
    tabContainer.append("<li><a href='#dboxTabLogging'>DOR</a></li>");
    tabContainer.append("<li><a href='#dboxTabMisc'>Misc</a></li>");
    tabContainer.css({
      "margin-bottom": "10px",
      "border-bottom": "1px solid #888888"
    });

    // Tab contents
    addDiagnosticsTab();
    addLoggingTab();
    addMiscTab();

    // Hi-yo Silver, away!
    _statusDisplay.tabs();
    _statusDisplay.find(".ui-widget-header").removeClass("ui-widget-header");
    _statusDisplay.find(".ui-tabs-panel").removeClass("ui-tabs-panel");
    _statusDisplay.find("a").first().click();
  }

  function addDiagnosticsTab() {
    _statusDisplay.append("<div id='dboxTabDiagnostics'></div>");
    var tabDiagnostics = jq("#dboxTabDiagnostics");
    tabDiagnostics.appendTo(_statusDisplay);

    tabDiagnostics.append("<table id='servicePingStatusTable' width='100%'></table>");
    _statusTable = jq("#servicePingStatusTable");

    tabDiagnostics.append("<br><div id='servicePingDiagCount'>No diagnostics</div>");
    _statusDiagCount = jq("#servicePingDiagCount");

    tabDiagnostics.append("<a id='diagnosticsWtfLink' href='https://bmwnext-build.bmwgroup.net:4000/confluence/display/systemBmwNext/Services+Overview+Monitoring' target='_blank'>What is this?</a>");
    var wtfLink = jq("#diagnosticsWtfLink");
    wtfLink.css("display", "block");
    wtfLink.css("color", "#777777");
    wtfLink.css("font-size", "10px");
    wtfLink.css("margin-top", "6px");
  }

  function addLoggingTab() {
    _statusDisplay.append("<div id='dboxTabLogging'></div>");
    updateLoggingStatus();
  }

  function addMiscTab() {
    _statusDisplay.append("<div id='dboxTabMisc'></div>");
    var tabMisc = jq("#dboxTabMisc");

    tabMisc.append("<b>De-minify Javascript</b><br/><br/>");
    addMiscTab_DebugJs(tabMisc);

    tabMisc.append("<b>Vehicle Manual</b><br/><br/>");
    addMiscTab_vehicleManualTestPage(tabMisc);

    tabMisc.append("<b>Bypass Akamai</b><br/><br/>");
    tabMisc.append("<div>Add a random parameter to certain AJAX requests in order to bypass Akamai.</div><br/>");
    addMiscTab_randomGlobalURLs(tabMisc);
    addMiscTab_randomJavascriptURLs(tabMisc);

    tabMisc.append("<br/><br/>");
    tabMisc.append("<b>Set wcmmode paramter to disabled</b><br/><br/>");
    addMiscTab_wcmModeGlobalURLs(tabMisc);
  }

  function addMiscTab_DebugJs(tabMisc) {
    var debugJsCheckbox = jq("<input type='CHECKBOX' id='dboxDebugJs'>");
    tabMisc.append(debugJsCheckbox);
    tabMisc.append(" <label for='dboxDebugJs'>Set debugJs cookie</label><br><br>");

    var cookie = rlCookieController.getCookie({cookieKey:'debugJs'});
    if ( cookie != null && cookie.cookieValue === "on") {
      debugJsCheckbox.attr("checked", "checked");
    }

    debugJsCheckbox.change(function() {
      window.setTimeout(function() {
        if (debugJsCheckbox.is(":checked")) {
          rlCookieController.setCookie({'cookieKey': "debugJs", 'cookieValue':'on'});
        } else {
          rlCookieController.setCookie({'cookieKey': "debugJs", 'cookieValue':''});
        }
      }, 0);
    });
  }

  function addMiscTab_vehicleManualTestPage(tabMisc) {
    var vehicleManualTestPageCheckbox = jq("<input type='CHECKBOX' id='dboxVehicleManualTestPage'>");
    tabMisc.append(vehicleManualTestPageCheckbox);
    tabMisc.append(" <label for='dboxDebugJs'>Show test page</label><br><br>");

    var cookie = rlCookieController.getCookie({cookieKey:'vehicleManualTestPage'});
    if (cookie != null && cookie.cookieValue === "on") {
      vehicleManualTestPageCheckbox.attr("checked", "checked");
    }

    vehicleManualTestPageCheckbox.change(function () {
      window.setTimeout(function () {
        if (vehicleManualTestPageCheckbox.is(":checked")) {
          rlCookieController.setCookie({'cookieKey': "vehicleManualTestPage", 'cookieValue':'on'});
        } else {
          rlCookieController.setCookie({'cookieKey': "vehicleManualTestPage", 'cookieValue':''});
        }
      }, 0);
    });
  }

  function addMiscTab_wcmModeGlobalURLs(tabMisc) {
    var cookie = rlCookieController.getCookie({cookieKey:'wcmModeGlobalUrls'});
    var wcmModeGlobalUrls = (cookie != null && cookie.cookieValue === "on");
    var origValues = {};

    var wcmModeUrlsCheckbox = jq("<input type='CHECKBOX' id='dboxWcmModeGlobalParams'>");
    var setWcmMode = function() {
      var regex = /^GLOBAL_(.*)_(URL|PATH)$/;
      var ts = new Date().getTime();
      for (varName in window) {
        if (regex.test(varName) && typeof window[varName] === "string") {
          console.log("Adding wcmMOde parameter to: " + varName);
          var path = window[varName];
          origValues[varName] = path;
          if (path.indexOf("wcmmode=disabled") == -1){
            path = path + (path.indexOf("?") >= 0 ? "&" : "?") + "wcmmode=disabled";
            console.log("Adding wcmMOde parameter to: " + varName );
          }
          window[varName] = path;
        }
      }
    };
    var restoreWcmMode = function() {
      jq.each(origValues, function(key, val) {
        console.log("Restoring URL: " + key);
        window[key] = val;
      })
    };
    if (wcmModeGlobalUrls) {
      wcmModeUrlsCheckbox.attr("checked", "checked");
      setWcmMode();
    }
    wcmModeUrlsCheckbox.click(function() {
      window.setTimeout(function() {
        if (wcmModeUrlsCheckbox.is(":checked")) {
          rlCookieController.setCookie({'cookieKey': "wcmModeGlobalUrls", 'cookieValue':'on'});
          setWcmMode();
        } else {
          rlCookieController.setCookie({'cookieKey': "wcmModeGlobalUrls", 'cookieValue':'off'});
          restoreWcmMode();
        }
      }, 0);
    });
    tabMisc.append(wcmModeUrlsCheckbox);
    tabMisc.append(" <label for='dboxWcmModeGlobalParams'>GLOBAL_*_URL|PATH variables</label><br>");
  }
  function addMiscTab_randomGlobalURLs(tabMisc) {
    var cookie = rlCookieController.getCookie({cookieKey:'randomiseGlobalUrls'});
    var randomiseGlobalUrls = (cookie != null && cookie.cookieValue === "on");
    var origValues = {};

    var randomiseUrlsCheckbox = jq("<input type='CHECKBOX' id='dboxRandomGlobalParams'>");
    var randomise = function() {
      var regex = /^GLOBAL_(.*)_(URL|PATH)$/;
      var ts = new Date().getTime();
      for (varName in window) {
        if (regex.test(varName) && typeof window[varName] === "string") {
          console.log("Adding random parameter to: " + varName);
          var path = window[varName];
          origValues[varName] = path;
          path = path + (path.indexOf("?") >= 0 ? "&" : "?") + "randomParam=" + ts;
          window[varName] = path;
        }
      }
    };
    var unrandomise = function() {
      jq.each(origValues, function(key, val) {
        console.log("Restoring URL: " + key);
        window[key] = val;
      })
    };
    if (randomiseGlobalUrls) {
      randomiseUrlsCheckbox.attr("checked", "checked");
      randomise();
    }
    randomiseUrlsCheckbox.click(function() {
      window.setTimeout(function() {
        if (randomiseUrlsCheckbox.is(":checked")) {
          rlCookieController.setCookie({'cookieKey': "randomiseGlobalUrls", 'cookieValue':'on'});
          randomise();
        } else {
          rlCookieController.setCookie({'cookieKey': "randomiseGlobalUrls", 'cookieValue':'off'});
          unrandomise();
        }
      }, 0);
    });
    tabMisc.append(randomiseUrlsCheckbox);
    tabMisc.append(" <label for='dboxRandomGlobalParams'>GLOBAL_*_URL|PATH variables</label><br>");
  }

  function addMiscTab_randomJavascriptURLs(tabMisc) {
    var cookie = rlCookieController.getCookie({cookieKey:'randomiseJavascriptUrls'});
    var randomiseJavascriptUrls = (cookie != null && cookie.cookieValue === "on");
    var randomiseUrlsCheckbox = jq("<input type='CHECKBOX' id='dboxRandomJavascriptParams'>");

    if (randomiseJavascriptUrls) {
      randomiseUrlsCheckbox.attr("checked", "checked");
    }
    randomiseUrlsCheckbox.click(function() {
      window.setTimeout(function() {
        if (randomiseUrlsCheckbox.is(":checked")) {
          rlCookieController.setCookie({'cookieKey': "randomiseJavascriptUrls", 'cookieValue':'on'});
          if (confirm("Page reload required. Reload now?")) {
            window.location.reload();
          }
        } else {
          rlCookieController.setCookie({'cookieKey': "randomiseJavascriptUrls", 'cookieValue':'off'});
        }
      }, 0);
    });

    tabMisc.append(randomiseUrlsCheckbox);
    tabMisc.append(" <label for='dboxRandomJavascriptParams'>JavascriptLoader calls</label>");
  }

  function updateLoggingStatus() {
    var cookie = rlCookieController.getCookie({cookieKey:'coreLogging'});
    var coreLoggingOn = (cookie != null && cookie.cookieValue === "on");

    // Header and current logging status information
    var header = jq("<div></div>");
    header.append("<div>DOR logging is currently <b>" + (coreLoggingOn ? "ON" : "OFF") + "</b></div>");

    // Checkbox to turn logging on/off
    var coreLoggingCheckbox = jq("<input id='coreLogging' type='CHECKBOX'" + (coreLoggingOn ? "CHECKED" : "") + ">");
    var coreLoggingLabel = jq("<label for='coreLogging'> Activate logging for this tag:</label>");
    var coreLoggingCheckboxAndLabel = jq("<div></div>");
    coreLoggingCheckboxAndLabel.css({
      "padding-top": "10px",
      "padding-bottom": "5px"
    });
    coreLoggingCheckbox.appendTo(coreLoggingCheckboxAndLabel);
    coreLoggingLabel.appendTo(coreLoggingCheckboxAndLabel);

    // Input field for username
    var cookie = rlCookieController.getCookie({cookieKey:'coreLoggingUsername'});
    var usernameTextbox = jq("<input id='loggingTag' value='" + ( cookie != null && cookie.cookieValue || "") + "'>");
    usernameTextbox.css({
      "width": "100%",
      "margin-bottom": "10px",
      "padding-top": "3px",
      "padding-bottom": "3px"
    });

    // Label that we successfully saved
    var didSaveLabel = jq("<div class='coreLoggingDidSave'>Saved! &nbsp;</div>");
    didSaveLabel.css({
      "display": "none",
      "margin-top": "4px",
      "float": "right"
    });

    // Button to show DOR
    var openDORLink = jq("<a>Goto DOR</a>");
    openDORLink.click(function() {
      var dorLink = rlPage.getNextCoreUrl() + "/internal/dor";

      var tag = jq("#loggingTag").val();
      if (tag) {
        dorLink += "/go?tag=" + tag;
      }
      window.open(
        dorLink,
        '_blank'
      );
    });

    openDORLink.attr("href", "#");
    openDORLink.css({
      "display": "block",
      "margin-top": "4px",
      "color": "black",
      "text-decoration": "underline"
    });

    // Button to save changes
    var saveChangesButton = jq("<input type='BUTTON'>");
    saveChangesButton.val("Save changes");
    saveChangesButton.css({
      "padding": "4px",
      "float": "right"
    });

    saveChangesButton.click(function() {
      var username = jq.trim(usernameTextbox.val());
      if (coreLoggingCheckbox.is(":checked") && username == "") {
        alert("You must specify a logging tag, e.g. a QC defect ID or your QX number.");
        return;
      }

      if (coreLoggingCheckbox.is(":checked")){
        rlCookieController.setCookie({'cookieKey': "coreLogging", 'cookieValue':'on'});
        rlCookieController.setCookie({'cookieKey': "coreLoggingUsername", 'cookieValue':username});
      }
      else{
        rlCookieController.setCookie({'cookieKey': "coreLogging", 'cookieValue':'off'});
      }
      updateLoggingStatus();

      jq(".coreLoggingDidSave").fadeIn(500).delay(1500).fadeOut(500)
    });

    // Build the GUI
    var gui = jq("#dboxTabLogging");
    gui.empty();
    header.appendTo(gui);
    coreLoggingCheckboxAndLabel.appendTo(gui);
    jq("<div></div>").append(usernameTextbox).appendTo(gui);
    saveChangesButton.appendTo(gui);
    didSaveLabel.appendTo(gui);
    openDORLink.appendTo(gui);
  }

  function createServiceTableRow(sID, label) {
    if (!label) label = sID;
    var domID = getDomIdForDiagnosticId(sID);
    var diag = getDiagnosticById(sID);
    if ( diag.infoURL ) {
      _statusTable.append("<tr id='Status_" + domID + "'><td class='sid'><a href=javascript:void(0) onclick=javascript:rlDiagnosticsBox.openUrlInNewTab('" + sID + "')>" + label + "</a></td><td class='status'>(New)</td></tr>");
    }
    else {
      _statusTable.append("<tr id='Status_" + domID + "'><td class='sid'>" + label + "</td><td class='status'>(New)</td></tr>");
    }
    jq("#Status_" + domID + " > td.status").css("text-align", "right");
    var domLabel = jq("#Status_" + domID + " > td.sid");
    domLabel.css("padding-right", "20px");
    domLabel.css("cursor", "pointer");
    if (! diag.infoURL ) {
      domLabel.click(function() {
        var diag = getDiagnosticById(sID);
        var show = "";
        if (diag.history.length === 0) {
          show = "No history for this diagnostic.";
        } else {
          for (var i=0; i<diag.history.length; i++) {
            show += diag.history[i] + "\r\n";
          }
        }
        alert(show);
      });
    }
  }
  function getInfoUrl(sID){
    var diag = getDiagnosticById(sID);
    var tmpUrl ="";
    if ( diag.infoURL ) {
      // prepare common replacements
      tmpUrl = rlMandator.replaceCoreUrl(diag.infoURL);

      // prepare perso replacements
      tmpUrl = tmpUrl.replace(/_{persoJsonParams}/g, (typeof rlOfferArea != "undefined" ? (escape(rlOfferArea.getPersoJsonParams())) : ""));
    }
    return tmpUrl;
  }

  function openUrlInNewTabImpl(sID) {
    var win = window.open(getInfoUrl(sID), '_blank');
    win.focus();
  }

  function setRunningDiags(c) {
    _runningDiags = c;
    if (c == 0) {
      if (_unsuccessfulDiags == 0) {
        _statusDiagCount.html("All done, no problems");
        _statusDiagCount.css("color", "green");
        _statusDiagCount.addClass("diagnosticsBoxAllDoneWithoutProblems");
      } else {
        _statusDiagCount.html("All done, with problems");
        _statusDiagCount.css("color", "red");
        _statusDiagCount.addClass("diagnosticsBoxAllDoneWithProblems");
      }
    } else {
      _statusDiagCount.html("Pending diagnostics: " + _runningDiags);
      _statusDiagCount.removeClass("diagnosticsBoxAllDone");
    }
  }

  /* ******************* Public interface ******************* */

  return {
    init: function() {
      rlComponentLoader.requireComponents(["jQuery_cookie", "jQuery_queryParams", "glassPaneLayerNext", "offerArea"]);
      rlComponentLoader.deferredExec(["jQuery_cookie", "jQuery_queryParams", "glassPaneLayerNext", "offerArea"], function() {
        initImpl();
      });
    },

    scanForEmbeddedDiagnosticIDs: function() {
      jq.each(jq(".diagnosticToRun"), function(index, data){
        addDiagnosticImpl(jq(data).text());
      });
    },

    addDiagnostic: function(diagnosticId) {
      addDiagnosticImpl(diagnosticId);
    },
    openUrlInNewTab: function(sID) {
      return openUrlInNewTabImpl(sID);
    }
  }
}(); 