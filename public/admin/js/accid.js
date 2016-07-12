/* 
* @Author: zhouyan
* @Date:   2015-12-30 20:16:04
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-04-18
*/
var $doc = $(document),$table =  $('#advertTB'),tableObj;
$(function  ()  {
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
        bStateSave:true, 
        sAjaxSource: "./getAllSponsorInfo",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
      aoData.push(
        {"name": "adStatus", "value": $('#adStatus').val()},
        {"name": "sponsor", "value": $('#sponsor').val()},
        {"name": "startTime", "value": $('#startTime').val()},
        {"name": "endTime", "value": $('#endTime').val()},
        {"name": "companyName", "value": $('#companyName').val()}
      )
    },
    createdRow: function ( row, data, index ) {
            var operate = '<a class="btn btn-info btn-sm" href="accidDetail?adID='+data.adID+'&type=view">查看详情</a>';
            $('td',row).eq(0).text(index+1);
            $('td',row).eq(3).text(checkStatus(data.adStatus));
            $('td',row).eq(5).text(setTimes(data.createTime));
            if(data.adStatus!="1" && data.adStatus!="2" && data.adStatus!="3" && data.adStatus!="4"){
              operate+='<a class="btn btn-warning btn-sm" href="accidDetail?adID='+data.adID+'&type=check">处理申请</a>';
            }
            if(data.adStatus=="3"){
                operate+='<button class="btn btn-success btn-sm active">开启投放</button>';
            }
            if(data.adStatus=="1"){
                operate+='<button class="btn btn-danger btn-sm active">暂停投放</button>';
              }
          $('td',row).eq(7).html(operate);
        },
        columns: [
            { data: "id" },
            { data: "adID" },
            { data: "sponsorName" },
            { data: "adStatus" },
            { data: "receiptID" },
            { data: "updateTime" },
            { data: "companyName" },
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
//点击开启投放、关闭投放
function change(data){
   var adStatus = data.adStatus=='1'?'3':'1';
   var reason = data.adStatus== '1'?'管理员强制暂停':'管理员开启投放';
    $.ajax({
        url:'handleAccidApply',
        type:'post',
        dataType:'json',
        data:{adID:data.adID,adStatus:adStatus,reason:reason}
    }).done(function(data){
        if (data.ERRORCODE=='0') {
            //tableObj.ajax.reload();
            location.reload();
        }else{
            alert('投放失败');
        }
    })
};
$doc.on('click','.active',function(){
    var data = tableObj.row( $(this).parents('tr') ).data();
    console.log(data.adStatus);
    var adStatus = data.adStatus=='3'?'确定开启投放？':'确定暂停投放？';
    var r = confirm(adStatus);
    if (r) {
        change(data);
    }
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
