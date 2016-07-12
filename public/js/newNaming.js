/* 
* @Author: wanghuilin
* @Date:   2016-05-05
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-05-31
*/

/*声明变量*/
var base_URL = '/adtalk';
var confirmInfo = false,checkForm,$doc=$(document);
var timeInterval="";

$(function(){
  getUserDetailInfo();
  /*表单验证开始*/
  checkForm = $('#advertiseForm').validate({      
    rules: {
      header: {
          required: true,
      },
      city: {
          required: true,
      },
      brandName: {
        required: true,
      },
      logo: {
        required: true,
      }
    }, 
    messages: {
      header: {
          required: '请填写广告标题'
      },
      city: {
          required: '请选择省份城市'
      },
      brandName: {
        required: '请输入品牌名称'
      },
      logo: {
        required: '请上传图标'
      }
    },
    errorPlacement: function(error, element) {
      if (element.is(':radio'))
        error.appendTo(element.parent().next().next());
      else if (element.is('#phoneNumber')) {
        error.insertAfter(element.next());
      } else if (element.is('#city')) {
        error.insertAfter(element.parent().prev().children('select#province'));
      } else{
        error.insertAfter(element);
      }
    }
  });
  countCityPrice();//计算价格
  checkPerfectInfo();//检查是否完善资料

});

function getUserDetailInfo(){
  $.ajax({
    url: 'getAdDetails',
    type: 'POST',
    dataType: 'json',
    data: {adID: $('#adID').val()},
    success:function(data) {
      if(data.ERRORCODE=="0"){
        if(data.RESULT[0].adStatus==2){
          console.log('编辑');
          $('#advertiseForm h5').html('<span class="gray">编辑 / </span>冠名广告');
          $('.adManage').addClass('current');
          $('#submit').hide();
          $('#save').show();
          editDetail(data.RESULT[0]);
        }
      }else{
        console.log('新建');
        $('#advertiseForm h5').html('新建冠名广告');   
        $('.newNaming').addClass('current');
        $('#submit').show();
        $('#save').hide();
        addInfo();
      }
    },
    error:function(){
      console.log('ajaxerror')
    }
  })
}

/*广告管理--点击编辑*/
function editDetail(data){
  var details = {cityCode:data.cityCode,adShape:1,timeSlot:data.timeInterval};
  //广告标题
  $('#advertiseTitle').val(data.advertiseTitle);
  //投放地区
  cityDetail(data);
  $('#cityCodeList').val(data.cityCode);
  countCityPrice();
  //getPriceList(details);
  //选择时间
  timeDetail(data);
   //价格
  var priceArr = data.advertisePrice.split('~');
  $('#minPrice').html(priceArr[0]);
  $('#maxPrice').html(priceArr[1]);
  console.log($('#minPrice').html(),$('#maxPrice').html(),priceArr[0],priceArr[1]);
  //广告位
  $("input[name='adType'][value= "+data.adSpace+"]").attr("checked",true);
  if (data.adSpace==4) {
    $('.bgColor').removeClass('none');
  }
  //品牌名称
  $('#brandName').val(data.brandName);
  //背景色
  $('#wordColor').val(data.wordColor);
  $('#pageColor').css('background',data.wordColor);
  $('.markBg .markName').css('color',data.wordColor);
  $('#nowColor').val(data.bgcolor);
  $('#pageColorViews').css('background',data.bgcolor);
  $('.markBg').css('border-bottom-color',data.bgcolor);
  //备注
  $('#remark').val(data.remark);
  //图片
  $('#logoImg').attr('src',data.logoURL); 
  //$('#logoURL').val(data.logoURL);   
  $('.target').attr('src',data.logoURL);
  $('.jcrop-holder img').attr('src',data.logoURL);
  $('.previewLogo').attr('src',data.logoURL); 
}

/*新建广告*/  
function addInfo(){
  //广告标题
  $('#advertiseTitle').val();
  //投放地区
  $('#nationwide').prop('checked',true);
  //选择时间
  $('#Unlimited').prop('checked',true);
  //品牌名称
  $('#brandName').val();
  //广告位
  $('#roadVoice').prop('checked',true);
  //背景色
  $('#wordColor').val('#FFF');
  $('#pageColor').css('background','#FFF');
  $('.markBg .markName').css('color','#FFF');
  $('#nowColor').val('#F47923');
  $('#pageColorViews').css('background','#F47923');
  $('.markBg').css('border-bottom-color','#F47923');
  //备注
  $('#remark').val();
  //图片
  $('#logoImg').attr('src','../../public/images/nopic.jpg');
  $('#logoUrl').val();
  $('.target').attr('src','../../public/images/nopic.jpg');
  $('.jcrop-holder img').attr('src','../../public/images/nopic.jpg');
  $('.previewLogo').attr('src','../../public/images/nopic.jpg'); 
}
/*获取时间和广告形式价格*/
(function showPriceList(){
  $.ajax({
    url: 'showPriceList',
    type: 'POST',
    dataType: 'json',
    success:function(data){
      if (data.ERRORCODE == '0') {
        var timeSlotInfo = data.RESULT.timeSlotInfo;
        var adShapePrice = data.RESULT.adShapePrice;

        //给广告形式赋值价格
        $('#adShapePrice').val(adShapePrice[0].price);
        //时间动态赋值价格
        if($('input[name=genre]:checked').val()=="1"){
          $('#timeTitle').html('<dd class="text-success">全天24小时</dd>');
        }
        var timeSlot = '',len = timeSlotInfo.length;
        for (var i = 0; i < len; i++) {
          timeSlot +='<dd><input class="chk" type="checkbox" name="time" data-labelauty="'+timeSlotInfo[i].timeSlot+'" value="'+timeSlotInfo[i].timeSlot+'" data-price="'+timeSlotInfo[i].price+'"></dd>'
        };
        $('.dowebok').append(timeSlot);
        $('.dowebok input[type=checkbox]').labelauty().prop('checked',true);
      } else{
        console.log('error')
      };
    },
    error:function(){
      console.log('ajaxerror')
    }
  })
}());

/*查看价格明细详情*/
$doc.on('click', '#details', function(event) {
  $('input[name=time]:checked').each(function(){
    timeInterval += $(this).val()+'|';
  });
  var details = {
    cityCode:$('#cityCodeList').val(),
    adShape:1,
    timeSlot:timeInterval,
  }
  getPriceList(details);
});

/*预览真实效果*/
$doc.on('click','.realEffect',function(){
  $('.markName').html($('#brandName').val());
  $('.markBg').css('border-bottom-color',$('#nowColor').val());
  $('.markBg .markName').css('color',$('#wordColor').val());
  check();
});

/*若无LOGO，上传图片*/
$doc.on('click', '#logo',function(){
  $('#submit').prop('disabled',false);
})

/*点击保存*/
$doc.on('click', '#save',function(){
  $('#save').prop('disabled',true);
  editInfo(204,204,'logoImg','editAdInfo');
});

/*点击提交审核*/
$doc.on('click', '#submit',function(){
  $('#submit').prop('disabled',true);
  referInfo(204,204,'logoImg','adSubmitCheck');
});

/*点击审核提交按钮*/
function sumbitinfo(infoUrl){
  if(!$('input[name=time]:checked').length && $('input[name=genre]:checked').val() !="1" ){
    $('.submit-title').html('<p class="text-danger">请至少选择一个投放时间段</p>');
    return false;
  }
  $('input[name=time]:checked').each(function(){
    timeInterval += $(this).val()+'|';
  });
  if ($('#logoURL').val()=='') {
    var data ={
      userID:$('#userID').val(),
      adID: $('#adID').val(),
      adShape:1,
      adStatus:0,
      advertiseTitle:$('#advertiseTitle').val(),
      cityCode:$('#cityCodeList').val(),
      timeType:$('input[name=genre]:checked').val(),
      timeInterval:timeInterval,
      brandName:$('#brandName').val(),
      remark:$('#remark').val(),
      wordColor:$('#wordColor').val(),
      bgcolor:$('#nowColor').val(),
      adSpace:$('input[name=adType]:checked').val(),
      maxPrice:$('#maxPrice').text(),
      minPrice:$('#minPrice').text()
    }
  }else{
    var data={
      userID:$('#userID').val(),
      adID: $('#adID').val(),
      adShape:1,
      adStatus:0,
      advertiseTitle:$('#advertiseTitle').val(),
      cityCode:$('#cityCodeList').val(),
      timeType:$('input[name=genre]:checked').val(),
      timeInterval:timeInterval,
      brandName:$('#brandName').val(),
      remark:$('#remark').val(),
      logoURL:$('#logoURL').val(),
      wordColor:$('#wordColor').val(),
      bgcolor:$('#nowColor').val(),
      adSpace:$('input[name=adType]:checked').val(),
      maxPrice:$('#maxPrice').text(),
      minPrice:$('#minPrice').text()
    }
  }
  $.ajax({
    url: infoUrl,
    type: 'POST',
    dataType: 'json',
    data:data,
    success:function(data){
      if(data.ERRORCODE=="0"){
        window.location.href = 'advertiseManage';
      }else if(data.ERRORCODE=="2002"){
        $('#submit').prop('disabled',false);
        $('#save').prop('disabled',false);
        $('.submit-title').html('<p class="text-danger">价格计算不一致，请核对</p>');
      }else if(data.ERRORCODE=="1003"){
        $('#submit').prop('disabled',false);
        $('#save').prop('disabled',false);
        $('.submit-title').html('<p class="text-danger">添加广告失败，请稍后</p>');
      }
    }
  })
}

/*广告位*/
$doc.on('click','.adType',function(){
  AdPosition();
})

function AdPosition(){
  var selectedvalue = $("input[name='adType']:checked").val();
  if(selectedvalue == 1){
    $('.roadLogo').addClass('road');
    $('.roadLogo').removeClass('dog');
    $('.roadLogo').removeClass('yuliao');
    $('.roadLogo').show();
    $('.markLogo').hide();
    $('.preview-pane-3').hide();
    $('.preview-pane-4').show();
    $('.bgColor').hide();
  }else if (selectedvalue == 2) {
    $('.roadLogo').removeClass('road');
    $('.roadLogo').addClass('dog');
    $('.roadLogo').removeClass('yuliao');
    $('.roadLogo').show();
    $('.markLogo').hide();
    $('.preview-pane-3').show();
    $('.preview-pane-4').hide();
    $('.bgColor').hide();
  }else if (selectedvalue == 3){
    $('.roadLogo').removeClass('road');
    $('.roadLogo').removeClass('dog');
    $('.roadLogo').addClass('yuliao');
    $('.roadLogo').show();
    $('.markLogo').hide();
    $('.preview-pane-3').show();
    $('.preview-pane-4').hide();
    $('.bgColor').hide();
  }else{
    $('.roadLogo').hide();
    $('.markLogo').show();
    $('.preview-pane-3').hide();
    $('.preview-pane-4').show();
    $('.bgColor').show();
  }
}


