/* 
* @Author: wanghuilin
* @Date:   2016-05-05
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-05-12
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
      audioContent: {
          required: true,
      },
      voices: {
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
      audioContent: {
          required: '请输入广告语音内容'
      },
      voices: {
          required: '请上传语音'
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

/*判断新建  或 编辑*/
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
          $('#advertiseForm h5').html('<span class="gray">编辑 / </span>尾标广告');
          $('.adManage').addClass('current');
          $('#submit').hide();
          $('#save').show();
          editDetail(data.RESULT[0]);
        }
      }else{
        console.log('新建');
        $('#advertiseForm h5').html('新建开机广告');   
        $('.newTrailer').addClass('current');
        $('#submit').show();
        $('#save').hide();
        addInfo();
      }
    },
    error:function(){
      console.log('ajaxerror');
    }
  })
}

/*广告管理--点击编辑*/
function editDetail(data){
  var details = {cityCode:data.cityCode,adShape:2,timeSlot:data.timeInterval};
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
  //上传语音
  $("#audio").attr("src", data.mp3URL);
  $("#audio")[0].pause();
  $("#audio").show();
  //$fileSize=data.fileSize;
  console.log(data.fileSize);
  //语音内容
  $('#audioContent').val(data.audioContent);
  //备注
  $('#remark').val(data.remark);
}

/*新建广告*/
function addInfo(){
  //广告标题
  $('#advertiseTitle').val();
  //投放地区
  $('#nationwide').attr('checked',true);
  //选择时间
  $('#Unlimited').attr('checked',true);
  //上传语音
  $('#audioFile').val();
  $("#audio").attr("src",'');
  $("#audio")[0].pause();
  $("#audio").hide();
  //语音内容
  $('#audioContent').val();
  //备注
  $('#remark').val();
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
        $('#adShapePrice').val(adShapePrice[1].price);
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
    adShape:2,
    timeSlot:timeInterval,
  }
  getPriceList(details);
});

/*上传音频并播放*/
$doc.on("change","#upload",function () {
  var obj = document.getElementById('upload'),
      filetypes = ['.mp3'],
      error = 'audioError',
      size = 5*1024;
  getPhotoSize(obj,filetypes,error,size);
  var objUrl = getObjectURL(this.files[0]);
  $("#audio").attr("src", objUrl);
  $("#audio")[0].pause();
  $("#audio").show();
  getTime();
  var v = $(this).val();
  var reader = new FileReader();
  reader.readAsDataURL(this.files[0]);
  $('#fileSize').val(this.files[0].size);
  reader.onload = function(e){
    $('#audioFile').val(e.target.result);
  }
});

/*点击保存*/
$doc.on('click', '#save',function(){
  if ($('#audioFile').val()==''){
    sumbitinfo('editAdInfo');
  }else{
    if (checkForm.form()) {
      $('#save').prop('disabled',true);
      sumbitinfo('editAdInfo');
    }
  }
});

/*点击提交审核*/
$doc.on('click', '#submit',function(){
  if (checkForm.form()) {
    $('#submit').prop('disabled',true);
    sumbitinfo('adSubmitCheck');
  }
});

/*提交表单信息*/
function sumbitinfo(infoUrl){
  if(!$('input[name=time]:checked').length && $('input[name=genre]:checked').val() !="1" ){
    $('.submit-title').html('<p class="text-danger">请至少选择一个投放时间段</p>');
    return false;
  }
  $('input[name=time]:checked').each(function(){
    timeInterval += $(this).val()+'|';
  });
  if ($('#audioFile').val()=='') {
    var data={
      userID:$('#userID').val(),
      adID: $('#adID').val(),
      adShape:2,
      adStatus:0,
      advertiseTitle:$('#advertiseTitle').val(),
      cityCode:$('#cityCodeList').val(),
      timeType:$('input[name=genre]:checked').val(),
      timeInterval:timeInterval,
      audioContent:$('#audioContent').val(),
      remark:$('#remark').val(),
      maxPrice:$('#maxPrice').html(),
      minPrice:$('#minPrice').html()
    }
  }else{
    var data={
      userID:$('#userID').val(),
      adID: $('#adID').val(),
      adShape:2,
      adStatus:0,
      advertiseTitle:$('#advertiseTitle').val(),
      cityCode:$('#cityCodeList').val(),
      timeType:$('input[name=genre]:checked').val(),
      timeInterval:timeInterval,
      audioURL:$('#audioFile').val(),
      audioContent:$('#audioContent').val(),
      remark:$('#remark').val(),
      fileSize:$('#fileSize').val(),
      maxPrice:$('#maxPrice').html(),
      minPrice:$('#minPrice').html()
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
        console.log($('#minPrice').html(),$('#maxPrice').html());
        $('.submit-title').html('<p class="text-danger">价格计算不一致，请核对</p>');
      }else if(data.ERRORCODE=="1003"){
        $('#submit').prop('disabled',false);
        $('#save').prop('disabled',false);
        $('.submit-title').html('<p class="text-danger">添加广告失败，请稍后</p>');
      }
    }
  })
}

