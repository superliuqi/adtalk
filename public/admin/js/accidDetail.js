/* 
* @Author: wanghuilin
* @Date:   2016-04-13 
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-04-13
*/

'use strict';
var $doc = $(document);
$(function()  {
  getAcciDDetail();
});
function getAcciDDetail(){
  $.ajax({
      type: "post",
      url: 'getAccidDetail',
      dataType: "json",
      data: {adID: $('#adID').val()}
  }).done(function (data) {
    if(data.ERRORCODE=="0"){
      showDetail(data.RESULT);
      if($('#type').val()=="view"){
        showCheckDetial(data.RESULT);
      }
    }else{
      alert(data.RESULT);
    }
  });
}
//处理审核结果
$doc.on('click','#checkStatus',function (){
  if($(this).val()=="2"){
    $('#reason').removeClass('hidden');
    return true;
  }
  $('#reason').addClass('hidden');
  $('#submit').attr('disabled',false);
});

$doc.on('click','#submit',function (){
  var adID = $('#adID').val(),reason = $('#resonContent').val(),adStatus = $('#checkStatus').val(); 
  if(adStatus=="2"&&(adID=="" || reason=="")){
    alert("缺少必要参数");
    return false;
  }
  $(this).prop('disabled',true);
  $.ajax({
        type: "post",
        url: 'handleAccidApply',
        dataType: "json",
        data: {adID: adID,reason:reason,adStatus:adStatus}
    }).done(function (data) {
        if(data.ERRORCODE=="0"){
          alert("审核成功");
          window.location.href = 'accid';
        }else{
          alert(data.RESULT);
        }
    });
});
/*console.log(parseCityCode(370100));*/
juicer.register('parseCityCode',parseCityCode);
juicer.register('parseTimestmp',setTimes);
juicer.register('parseADStatus',checkStatus);
function showDetail(data){
  var data = {data:data};
  var detail = '{@each data as it}\
    <h3 class="text-info">新建意外险内容</h3>\
    <dl class="dl-horizontal"><dt>赞助商名称：</dt><dd>${it.sponsorName}</dd></dl>\
    <dl class="dl-horizontal"><dt>投放地区：</dt><dd id="cityName">${it.cityCode|parseCityCode}</dd></dl>\
    <dl class="dl-horizontal"><dt>赞助商图标：</dt><dd ><div id="preview-pane"><div class="preview-container"><img src="${it.sponsorLogo}" alt="暂无图标"></div></div></dd></dl>\
    <dl class="dl-horizontal"><dt>广告URL：</dt><dd>${it.sponsorUrl}</dd></dl>\
    <dl class="dl-horizontal"><dt>备注：</dt><dd>${it.remark}</dd></dl>\
    {@/each}';
  $('#adDetail').html(juicer(detail,data));
}
function showCheckDetial(data){
  var data = {data:data};
  var detail = '{@each data as it}\
    <h3 class="text-info">内容审核</h3>\
        <dl class="dl-horizontal"><dt>审核结果:</dt><dd>${it.adStatus|parseADStatus}</dd></dl>\
    {@if it.adStatus==2}\
      <dl class="dl-horizontal"><dt>原因：</dt><dd>${it.reason}</dd></dl>\
    {@/if}\
    <dl class="dl-horizontal"><dt>审核时间：</dt><dd>${it.updateTime|parseTimestmp}</dd></dl>\
    <p class="text-center">\
      <button style="width:150px;" type="button" class="btn btn-default btn-lg" onclick="history.back();">返回</button>\
    </p>\
  {@/each}';
  $('#checkDetial').html(juicer(detail,data));
}