//static json map for special case or the duplicate case
var staticTrackMap = {		
	"cn/zh/general/h5vco/index.html":"upper_H5VCO",
	"zh_CN/fastlane/fleet-sales/greeting.html":"Fleet_Sales",
	"zh_CN/index.html":"Home",
	"findyour.bmw.com.cn":"Need_Analyser",
	"bmwgroup.jobs/en_cn/home":"joinus",
	"iframe.bmw.com.cn/cn/zh/insights/technology/brand_experience_centre/index.html":"bec",
	"iframe.bmw.com.cn/cn/zh/insights/careers/overview.html":"passionfuture",
	"zh_CN/topics/insights/joy_2015.html":"bmwjoy",
	"zh_CN/topics/insights/events.html":"events",
	"cn/zh/insights/events/pool/aftersales_brand/2015/index.html":"brand",
	"cn/zh/insights/events/pool/7_series/2015/index.html":"7series",
	"cn/zh/insights/events/pool/mevent/index.html":"mevent",
	"cn/zh/insights/events/pool/xfamily/2015/index.html":"xfamily",
	"cn/zh/insights/events/pool/3series/2015/overview.html":"3series",
	"www.bmw.com/com/en":"insights_International_BMW_Site",
	"bmwtv.youku.com":"insights_Web_TV",
	"bmw.tmall.com":"insights_Tmall",
	"bmwgroup.jobs/en_cn/home":"insights_Join_us",
	};

//-------------split the link path into array by "/" ----------------------------------	
function getPathArray(jqLinkObj){
	var tempPath = jqLinkObj.get(0).pathname.replace(/(^\/+)|(\/+$)/g,"");
	tempPath = tempPath.replace(/\.[a-zA-Z0-9]+$/,"")
	return tempPath.replace(/[\._-]/g,"").split("/");
}

//-----------process the href value of tag a ------------------------------------------
function processHref(tagAlink){
	// http://www.bmw.com.cn/zh_CN/index.html/ -> zh_CN/index.html
	var regStr = "^(" + tagAlink.protocol + "//)?(" + window.location.hostname + ")?(:"+tagAlink.port+")?"; 
	var reg = new RegExp(regStr);
	var tempUrl = tagAlink.href.replace(reg,'');
	tempUrl = tempUrl.replace(/(^\/+)|(\/+$)/g,"");
	return tempUrl;
}

//----------------------get identifier for every level topic links---------------------
function getIdentifier(jqALink, level){
	var currIdtfy = "";
	var staticTrackKey = processHref(jqALink.get(0));
	if(staticTrackMap.hasOwnProperty(staticTrackKey)){
		currIdtfy = staticTrackMap[staticTrackKey];
	}else{
		if(jqALink.hasClass("mainNavTopicsMoreLinks")){
			currIdtfy = getPathArray(jqALink).slice(-level)[0];
		}else{
			currIdtfy = getPathArray(jqALink).slice(-1)[0];
		}
	}
	var lastLevIdtfy  = "";
	if(level - 1 > 0 && jqALink.closest(".mainNavTopicItemsLevel"+level).prev().length > 0){
		lastLevIdtfy = getIdentifier(jqALink.closest(".mainNavTopicItemsLevel"+level).prev(), level-1);
	}
	return lastLevIdtfy + (lastLevIdtfy !="" ?"_" :"") + currIdtfy; 
}

//------------append Parameter function implementation-------------------------------
function appendParam(tagAlink, position){
	if(tagAlink.href.indexOf("insitebutton=") > -1){
		return;
	}
	var url = processHref(tagAlink);
	var currPathArry = getPathArray(jq(tagAlink));
	var trackCode = "";
	
	if(tagAlink.id.match(/^topNavi_topicsLevel([0-9]+)Link/)){
		trackCode = getIdentifier(jq(tagAlink), tagAlink.id.match(/^topNavi_topicsLevel([0-9]+)Link/)[1]);
	}else if(staticTrackMap && staticTrackMap.hasOwnProperty(url)){
		trackCode = staticTrackMap[url];
	}else if(tagAlink.id.match(/^topNavi_hybridLayer_topics_cmxLink/)){
		// seriesNo + "_"+ resourceName
		trackCode = jq(tagAlink).attr("data-series") + "_" + currPathArry.slice(-1)[0];
	}else if(tagAlink.id.match(/^topNavi_(image|title|cmx)Link/)){
		//modelRange + "_img"
		trackCode = jq(tagAlink).attr("data-modelrangeid") + "_img";
	}else if(tagAlink.id.match(/^topNavi_configLink/)){
		//modelRange + "_h5vco"
		trackCode = jq(tagAlink).attr("data-modelrangeid") + "_h5vco"; 
	}else if(tagAlink.id.match(/^bottomNavi_/)){
		//"the last but one part of url" + "_"+ resourceName
		trackCode = currPathArry.slice(-2).join("_");
	}	
	
	if(trackCode){
		var conn = (tagAlink.href.indexOf('?')==-1) ? '?' : '&';
		var hrefTrack = jq(tagAlink).attr("href") + conn + "insitebutton=1-All-" + position + "-Regular_" + trackCode +"-1";
		jq(tagAlink).attr("href", hrefTrack);
	}
}

//--------------------append parameter to tag a-------------------------------------
var doTrack = true;
jq(function(){
	if(!doTrack){
		console.log('Track flag is off!');
		return false;
	}
	jq(".menuBar a").unbind("click").bind("click",function(){     
		appendParam(this, "Header");
	});
	jq('.bottomNavContainer .bottomNavBlock a').unbind("click").bind("click",function(){     
		appendParam(this, "Footer");
	});
});
