/* 
* @Author: zhouyan
* @Date:   2015-10-14 18:49:15
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-22 19:44:32
*/

var $doc = $(document),$table =  $('#rechargeTB'),tableObj;
$(function (){
    var rechargeStatus = $('input[name="rechargeStatus"]').val();
    $('#rechargeStatus option[value="'+rechargeStatus+'"]').prop('selected','selected').change();
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
        sAjaxSource: "./getAllRechargeInfo",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
            aoData.push(
                {"name": "rechargeStatus", "value": $('#rechargeStatus').val()},
                {"name": "remitIdentCode", "value": $('#remitIdentCode').val()},
                {"name": "startTime", "value": $('#startTime').val()},
                {"name": "endTime", "value": $('#endTime').val()},
                {"name": "runCode", "value": $('#runCode').val()},
                {"name": "userID", "value": $('#userID').val()}
            )
        },
        createdRow: function ( row, data, index ) {
            $('td',row).eq(0).text(index+1);           
            $('td',row).eq(4).text(checkRechargeStatus(data.rechargeStatus));
            $('td',row).eq(5).text(setTimes(data.createTime));
            if(data.rechargeStatus=="0"){
                $('td',row).eq(7).html('<button class="submit">确认已打款</button>');
            }
        },
        columns: [
            { data: "id" },
            { data: "runCode" },
            { data: "remitIdentCode" },
            { data: "money" },
            { data: "rechargeStatus" },
            { data: "updateTime" },
            { data: "remark" },
            {
                defaultContent: ''
            }
        ]
    });
    laydate({
        elem: '#startTime',
        format: 'YYYY-MM-DD hh:mm'
    });
    laydate({
        elem: '#endTime',
        format: 'YYYY-MM-DD hh:mm'
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
//确认已打款
$doc.on('click','.submit',function(){
    //弹出确认框
    $('.submitS').css('display', 'inline-block');
    var data = tableObj.row($(this).parents('tr')).data();
    //将点击停用的id赋值给确认按钮的class
    $('#submitsure').attr('data-code',data.remitIdentCode);
});

//取消删除
$doc.on('click','#submitcancel',function(){
    //隐藏弹框，不刷新页面。
    $('.submitS').css('display', 'none');
});
//确认已打款
$doc.on('click','#submitsure',function(){
     $.ajax({
        url: './confirmPay',
        type: 'post',
        dataType: 'json',
        data: {
            remitIdentCode:$(this).attr('data-code'),
            userID:$('#userID').val()
        },
        success:function(data){
            $('.submitS').css('display', 'none');
            if(data.ERRORCODE=="0"){
                tableObj.ajax.reload();
            }else{
                alert(data.RESULT);
            }
        }
    })
});
