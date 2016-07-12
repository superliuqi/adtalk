/* 
* @Author: zhouyan
* @Date:   2015-10-14 18:49:15
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-05-24
*/
var $doc = $(document),$table =  $('#advertiseTB'),tableObj;
$(function (){
    var adStatus = $('input[name="adStatus"]').val();
    $('#adStatus option[value="'+adStatus+'"]').prop('selected','selected').change();
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
        sAjaxSource: "./getAllAdInfo",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
            aoData.push(
                {"name": "adStatus", "value": $('#adStatus').val()},
                {"name": "advertiseTitle", "value": $('#advertiseTitle').val()},
                {"name": "startTime", "value": $('#startTime').val()},
                {"name": "endTime", "value": $('#endTime').val()},
                {"name": "adShape", "value": $('#adShape').val()},
                {"name": "userID", "value": $('#userID').val()}
            )
        },
        createdRow: function ( row, data, index ) {
            var operate = '';
            $('td',row).eq(0).text(index+1); 
            $('td',row).eq(1).html('<div>'+data.advertiseTitle+'</div>');
            $('td',row).eq(2).text(checkadShape(data.adShape));
            $('td',row).eq(4).text(checkStatus(data.adStatus));
            $('td',row).eq(5).text(setTimes(data.createTime));
            $('td',row).eq(6).html('<div>'+data.remark+'</div>');
            if(data.adStatus=="1"){
                operate+='<button class="stop">暂停投放</button>';
            }
            if(data.adStatus=="2"){
                if (data.adShape=='1') {
                    operate+='<a class="btn btn-warning btn-sm edit" href="newNaming?adID='+data.adID+'">编辑</a>';
                }else if (data.adShape=='2') {
                    operate+='<a class="btn btn-warning btn-sm edit" href="newTrailer?adID='+data.adID+'">编辑</a>';
                }else if(data.adShape=='4'){
                    operate+='<a class="btn btn-warning btn-sm edit" href="newAdvertise?adID='+data.adID+'">编辑</a>';
                }else{
                    operate+='<a class="btn btn-warning btn-sm edit" href="newRoad?adID='+data.adID+'">编辑</a>';
                }
                operate+='<button class="submit">提交审核</button>';
            }
            if(data.adStatus=="3"){
                operate+='<button class="btn btn-success btn-sm start">开启投放</button>';
            }
            $('td',row).eq(7).html(operate);
        },
        columns: [
            { data: "id" },
            { data: "advertiseTitle" },
            { data: "adShape" },
            { data: "advertisePrice" },
            { data: "adStatus" },
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
$doc.on('click','#search',function(){
    var sTime = Date.parse(new Date($('#startTime').val())) / 1000,
    eTime = Date.parse(new Date($('#endTime').val())) / 1000
    if(eTime<sTime){
        alert("TIME ERROR");
    }else{
        tableObj.ajax.reload();
    }
});
//停用选择广告
$doc.on('click','.stop',function(){
    //弹出确认框
    $('.deleteS').css('display', 'inline-block').children('p').text('是否确定停用？');
    //获取到点击停用的广告id
    var data = tableObj.row($(this).parents('tr')).data();
    //将点击停用的id赋值给确认按钮的class
    $('#sure').attr('data-adID',data.adID).attr('data-adStatus',data.adStatus);
});
//开启 选择广告
$doc.on('click','.start',function(){
    //弹出确认框
    $('.deleteS').css('display', 'inline-block').children('p').text('是否确定启用？');
    //获取到点击停用的广告id
    var data = tableObj.row($(this).parents('tr')).data();
    //将点击停用的id赋值给确认按钮的class
    $('#sure').attr('data-adID',data.adID).attr('data-adStatus',data.adStatus);
});
//确认停用
$doc.on('click','#sure',function(){
    var adStatus = $(this).attr('data-adStatus')=="3"?1:3;
     $.ajax({
        url: 'disableAd',
        type: 'post',
        dataType: 'json',
        data: {
            adID:$(this).attr('data-adID'),
            adStatus:adStatus
        },
        success:function(data){
            //停用后弹框隐藏
            $('.deleteS').css('display', 'none');
            if(data.ERRORCODE=="0"){
                tableObj.ajax.reload();
            }else{
                alert(data.RESULT);
            }
        }
    })
});

//取消停用
$doc.on('click','#cancel',function(){
    //隐藏弹框，不刷新页面。
    $('.deleteS').css('display', 'none');
});

//提交审核广告
$doc.on('click','.submit',function(){
    //弹出确认框
    $('.submitS').css('display', 'inline-block');
    //获取到点击提交的广告id
     var data = tableObj.row($(this).parents('tr')).data();
    //将点击删除的id赋值给确认按钮的class
    $('#submitsure').attr('data-adID',data.adID);
});

//确认审核
$doc.on('click','#submitsure',function(){
     $.ajax({
        url: 'disableAd',
        type: 'post',
        dataType: 'json',
        data: {
            adID:$(this).attr('data-adID'),
            adStatus:0
        },
        success:function(data){
            //确认后弹框隐藏
            $('.submitS').css('display', 'none');
            if(data.ERRORCODE=="0"){
                tableObj.ajax.reload();                
            }else{
                alert(data.RESULT);
            }
        }
    })
});

//取消删除
$doc.on('click','#submitcancel',function(){
    //隐藏弹框，不刷新页面。
    $('.submitS').css('display', 'none');
});