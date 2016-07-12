/* 
* @Author: zhouyan
* @Date:   2016-01-07 23:51:02
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-06-01
*/

'use strict';
var $doc = $(document);
$(function	()	{
	getADDetail();
});
function getADDetail(){
	$.ajax({
        type: "post",
        url: 'getAdDetails',
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
        url: 'handleAdApply',
        dataType: "json",
        data: {adID: adID,reason:reason,adStatus:adStatus}
    }).done(function (data) {
        if(data.ERRORCODE=="0"){
        	alert("审核成功");
          window.location.href = 'advertising'; 
        }else{
        	alert("审核失败");
        }
    });
});
/*console.log(parseCityCode(370100));*/
juicer.register('checkadShape',checkadShape);
juicer.register('parseAdShape',parseAdShape);
juicer.register('parseCityCode',parseCityCode);
juicer.register('parseTimestmp',setTimes);
juicer.register('parseADStatus',checkStatus);
function showDetail(data){
	var data = {data:data};
	var detail = '{@each data as it}\
		<h3 class="text-info">广告内容</h3>\
    <dl class="dl-horizontal"><dt>广告标题：</dt><dd>${it.advertiseTitle}</dd></dl>\
    <dl class="dl-horizontal"><dt>投放地区：</dt><dd id="cityName">${it.cityCode|parseCityCode}</dd></dl>\
    <dl class="dl-horizontal"><dt>广告形式：</dt><dd>${it.adShape|checkadShape}</dd></dl>\
    {@if it.adShape==1}\
      <dl class="dl-horizontal"><dt>广告位：</dt><dd>${it.adSpace|parseAdShape}</dd></dl>\
      <dl class="dl-horizontal"><dt>品牌名称：</dt><dd>${it.brandName}</dd></dl>\
      <dl class="dl-horizontal"><dt>价格区间：</dt><dd>${it.advertisePrice} 元/条</dd></dl>\
      <dl class="dl-horizontal"><dt>图标：</dt><dd><div id="preview-pane" class="logo"><div class="preview-container"><img class="logo" src="${it.logoURL}" alt="暂无图标" /></div></div></dd></dl>\
    {@/if}\
    {@if it.adShape==4}\
      <dl class="dl-horizontal"><dt>文件：</dt><dd>${it.filename}</dd></dl>\
      <dl class="dl-horizontal"><dt>广告链接地址：</dt><dd>${it.sponsorUrl}</dd></dl>\
      <dl class="dl-horizontal"><dt>品牌名称：</dt><dd>${it.brandName}</dd></dl>\
      <dl class="dl-horizontal"><dt>价格区间：</dt><dd>${it.advertisePrice} 元/条</dd></dl>\
      <dl class="dl-horizontal"><dt>图标：</dt><dd><div id="preview-pane" class="logo"><div class="preview-container"><img class="logo" src="${it.logoURL}" alt="暂无图标" /></div></div></dd></dl>\
    {@/if}\
    {@if it.adShape==5}\
      <dl class="dl-horizontal"><dt>价格区间：</dt><dd>${it.advertisePrice} 元/条</dd></dl>\
      <dl class="dl-horizontal"><dt>图标：</dt><dd><div id="preview-pane" class="kanban"><div class="preview-container"><img class="logo" src="${it.logoURL}" alt="暂无图标" /></div></div></dd></dl>\
      <dl class="dl-horizontal"><dt>广告链接地址：</dt><dd>${it.sponsorUrl}</dd></dl>\
    {@/if}\
    {@if it.mp3URL}\
    <dl class="dl-horizontal"><dt>语音：</dt><dd><audio controls="" src="${it.mp3URL}"></audio></dd></dl>\
    <dl class="dl-horizontal"><dt>语音内容：</dt><dd>${it.audioContent}</dd></dl>\
    {@/if}\
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