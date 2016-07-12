/* 
* @Author: zhouyan
* @Date:   2015-10-14 18:49:15
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-16 16:30:44
*/
var $doc = $(document),$table =  $('#flowRecordTB'),tableObj;
$(function (){
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
        sAjaxSource: "./getMoneyChangeInfo",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
            aoData.push(
                {"name": "adID", "value": $('#adID').val()},
                {"name": "moneyType", "value": $('#moneyType').val()},
                {"name": "runCode", "value": $('#runCode').val()},
                {"name": "userID", "value": $('#userID').val()}
            )
        },
        createdRow: function ( row, data, index ) {
            $('td',row).eq(0).text(index+1);           
            $('td',row).eq(6).text(setTimes(data.updateTime));
            var moneyType="收入",code=data.runCode;
            $('td',row).eq(3).html('<span class="text-success">+ '+data.changedAmount+'</span>');
            if(parseFloat(data.changedAmount)<0){
               moneyType="支出";
               code=data.adID;
               $('td',row).eq(3).html('<span class="text-danger">'+data.changedAmount+'</span>');
            }
            $('td',row).eq(2).text(moneyType);
            $('td',row).eq(1).text(code);
        },
        columns: [
            { data: "id" },
            { data: "runCode" },
            { data: "remitIdentCode" },
            { data: "changedAmount" },
            { data: "amountAfterChanged" },
            { data: "remark" },
            { data: "updateTime" }
        ]
    });
});
$doc.on('click','#search',function(){
    tableObj.ajax.reload();
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
