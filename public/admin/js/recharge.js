/* 
* @Author: zhouyan
* @Date:   2016-01-08 14:36:13
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-22 19:21:05
*/

'use strict';
/* 
* @Author: zhouyan
* @Date:   2015-12-30 20:16:04
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-08 14:05:48
*/
var $doc = $(document),$table =  $('#rechargeTB'),tableObj;
$(function	()	{
    tableObj = $table.DataTable({
        oLanguage : { // 汉化
            sProcessing: "正在加载数据...",
            sLengthMenu: "显示_MENU_条 ",
            sZeroRecords : "没有您要搜索的内容",
            sInfo : "总记录数为 _TOTAL_ 条",
            sInfoEmpty : "记录数为0",
            sInfoFiltered : "(全部记录数 _MAX_  条)",
            sInfoPostFix : "",
            sSearch : "搜索",
            sUrl : "",
            oPaginate : {
                sFirst : "第一页",
                sPrevious : " 上一页 ",
                sNext : " 下一页 ",
                sLast : " 最后一页 "
            }
        },
        searching:false,
        bServerSide: true,
        sAjaxSource: "./getAllRecharge",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
			aoData.push(
				{"name": "rechargeStatus", "value": $('#rechargeStatus').val()},
				{"name": "companyName", "value": $('#companyName').val()},
				{"name": "startTime", "value": $('#startTime').val()},
				{"name": "endTime", "value": $('#endTime').val()},
				{"name": "remitIdentCode", "value": $('#remitIdentCode').val()},
				{"name": "runCode", "value": $('#runCode').val()}
			)
		},
		createdRow: function ( row, data, index ) {
            $('td',row).eq(0).text(index+1);
            $('td',row).eq(4).text(checkRechargeStatus(data.rechargeStatus));
            $('td',row).eq(5).text(setTimes(data.createTime));
            $('td',row).eq(8).text(setTimes(data.updateTime));
            if(data.rechargeStatus =="1"){
            	$('td',row).eq(9).html('<button class="btn btn-success btn-sm" id="showModel" data-toggle="modal" data-target="#myModal">处理申请</button>');
            }
        },
        columns: [
            { data: "id" },
            { data: "runCode" },
            { data: "remitIdentCode" },
            { data: "money" },
            { data: "rechargeStatus" },
            { data: "createTime" },
            { data: "companyName" },
            { data: "remark" },
            { data: "updateTime" },
            {
                defaultContent: ''
            }
        ]
    });
	laydate({
    	elem: '#startTime',
    	format: 'YYYY-MM-DD hh:mm',
	});
	laydate({
    	elem: '#endTime',
    	format: 'YYYY-MM-DD hh:mm',
	});
});
$doc.on('click','#search',function(){
	var sTime = Date.parse(new Date($('#startTime').val())) / 1000,
	eTime = Date.parse(new Date($('#endTime').val())) / 1000
	if(eTime<sTime){
		alert("TIME ERROR");
	}else{
		tableObj.ajax.reload();
	}
});
function retrieveData(sSource, aoData, fnCallback){
    $.ajax({
        type: "get",
        url: sSource,
        dataType: "json",
        data: {setData: JSON.stringify(aoData)}
    }).done(function (resp) {
        fnCallback(resp);
    });
}
$doc.on('change','#checkStatus',function (){
	if($(this).val()=="3"){
		$('#reason').removeClass('hidden');
		return true;
	}
	$('#reason').addClass('hidden');
});
$doc.on('click','#showModel',function (){
	var data = tableObj.row( $(this).parents('tr') ).data();
	$('#userID').val(data.userID);
	$('#runCodeH').val(data.runCode);
	$('#submit').prop('disabled',false);
});
$doc.on('click','#submit',function (){
	var userID = $('#userID').val(),reason = $('#resonContent').val(),
		runCode = $('#runCodeH').val(),checkStatus = $('#checkStatus').val();	
	if(checkStatus=="3" && (runCode=="" || userID=="" || reason=="")){
		alert("缺少必要参数");
		return false;
	}
	$(this).prop('disabled',true);
	$.ajax({
        type: "post",
        url: 'handleRecharge',
        dataType: "json",
        data: {userID: userID,reason:reason,runCode:runCode,rechargeStatus:checkStatus}
    }).done(function (data) {
        if(data.ERRORCODE=="0"){
        	$('#myModal').modal('hide');
        	tableObj.ajax.reload();
        }else{
        	alert(data.RESULT);
        }
    });
});