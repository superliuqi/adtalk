/* 
* @Author: zhouyan
* @Date:   2016-01-07 23:51:02
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-13 15:20:22
*/

'use strict';
var $doc = $(document);
$(function  ()  {
  getADDetail();
});
function getADDetail(){
  $.ajax({
        type: "post",
        url: 'selectAccountDetails',
        dataType: "json",
        data: {userID: $('#userID').val()}
    }).done(function (data) {
      if(data.ERRORCODE=="0"){
          showAccountInfo(data.RESULT);
          showCompanyData(data.RESULT);
          showOperatorInfo(data.RESULT);
          if($('#type').val()=="view"){
            showDataCheck(data.RESULT);
          }
      }else{
        alert(data.RESULT);
      }
    });
}
$doc.on('change','#checkStatus',function (){
  console.log($(this).val());
  if($(this).val()=="3"){
    $('#reason').removeClass('hidden');
    return true;
  }
  $('#reason').addClass('hidden');
});
$doc.on('click','#submit',function (){
  var userID = $('#userID').val(),reason = $('#resonContent').val(),checkStatus = $('#checkStatus').val(); 
  if(checkStatus=="4"&&(userID=="" || reason=="")){
    alert("缺少必要参数");
    return false;
  }
  $(this).prop('disabled',true);
  $.ajax({
        type: "post",
        url: 'handleApply',
        dataType: "json",
        data: {userID: userID,reason:reason,checkStatus:checkStatus}
    }).done(function (data) {
        if(data.ERRORCODE=="0"){
          alert("审核成功");
        }else{
          //$(this).prop('disabled',false);
          alert(data.RESULT);
        }
    });
});
juicer.register('getTradeList',function (indexs){
  return companyType[indexs];
});
juicer.register('parseCityCode',parseCityCode);
juicer.register('parseTimestmp',setTimes);
juicer.register('parseACStatus',checkStatus);
function showAccountInfo(data){
  var detail = '<h3 class="text-info">广告内容</h3>\
    <dl class="dl-horizontal col-sm-4"><dt>邮箱：</dt><dd>${email}</dd></dl>\
    <dl class="dl-horizontal col-sm-4"><dt>注册时间：</dt><dd>${createTime|parseTimestmp}</dd></dl>';
  $('#accountInfo').html(juicer(detail,data));
}
function showCompanyData(data){
  var detail = '<h3 class="text-info">企业资料</h3>\
    <dl class="dl-horizontal  col-sm-4"><dt>企业名称：</dt><dd>${companyName}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>行业类别：</dt><dd>${companyType|getTradeList}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>公司电话：</dt><dd>${companyPhone}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>营业执照：</dt><dd>${licenseNumber}</dd><dd ><img class="showPic" src="${licenseScanPreview}"></dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>组织机构代码证：</dt><dd>${orgCode}</dd><dd><img class="showPic" src="${orgScanPreview}"></dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>税务登记证：</dt><dd>${registrationNumber}</dd><dd><img class="showPic" src="${taxRegistrationPreview}"></dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>所在地区：</dt><dd>${cityCode|parseCityCode}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>公司地址：</dt><dd>${companyAddress}</dd></dl>';
  $('#companyData').html(juicer(detail,data));
}
function showOperatorInfo(data){
  var detail = '<h3 class="text-info">运营者信息</h3>\
    <dl class="dl-horizontal  col-sm-4"><dt>运营者姓名：</dt><dd>${name}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>运营者身份证号：</dt><dd>${IDNumber}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>运营者手机号码：</dt><dd>${telephone}</dd></dl>\
    <dl class="dl-horizontal  col-sm-4"><dt>运营者授权证明：</dt><dd><img class="showPic" src="${impowerProve}"></dd></dl>';
  $('#operatorInfo').html(juicer(detail,data));
}
function showDataCheck(data){
  var detail = '<h3 class="text-info">资料审核</h3>\
        <dl class="dl-horizontal"><dt>审核结果：</dt><dd id="checkStatus">${checkStatus|parseACStatus}</dd></dl>\
        <dl class="dl-horizontal"><dt>原因：</dt><dd>${reason}</dd></dl>\
    {@if checkStatus==2}\
    {@/if}\
    <dl class="dl-horizontal"><dt>审核时间：</dt><dd>${updateTime|parseTimestmp}</dd></dl>\
    <p class="text-center">\
      <button style="width:150px;" type="button" class="btn btn-default btn-lg" onclick="history.back();">返回</button>\
    </p>';
  $('#dataCheck').html(juicer(detail,data));
};
//放大图片
$doc.on('click','.showPic',function(){
  var src = $(this).attr('src');
  $("#overlay").removeClass('hidden');
  $(".bigPic").attr("src",src);
})
$doc.on('click','.bg',function(){
  $("#overlay").addClass('hidden');
});
//审核结果
function checkStatus(acStatus){
    switch(parseInt(acStatus)){
        case 0:
            return '未完善';
            break;
        case 1:
            return '待审核';
            break;
        case 2:
            return '审核通过';
            break;
        case 3:
            return '审核失败';
            break;
        default:
            return '无';
            break;
    }
};

//将城市邮编改为城市名
function parseCityCode(cityCode){
    var city='';
    if(cityCode=="1"){
        return "全国";
    };
    $.ajax({
        url: '../Advertise/getCityName',
        type: 'POST',
        async:false,
        dataType: 'json',
        data:{cityCode:cityCode}
    }).done(function(data){
            if(data.ERRORCODE=="0" && data.RESULT.length>0){
                var result = data.RESULT,len = result.length,i=0;
                for (; i < len; i++) {
                   city+=result[i].cityName;
                };
            }else{
                city = '';
            }
        }).fail(function() {
            city ='无数据';
        });
    return city;
};




