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
          required: '请填写广告标题',
      },
      city: {
          required: '请选择省份城市'
      },
      brandName: {
        required: '请输入品牌名称',
      },
      logo: {
        required: '请上传图标',
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
          $('#advertiseForm h5').html('<span class="gray">编辑 / </span>开机广告');
          $('.newStart').removeClass('current');
          $('.adManage').addClass('current');
          $('#submit').hide();
          $('#save').show();
          editDetail(data.RESULT[0]);
        }
      }else{
        console.log('新建');
        $('#advertiseForm h5').html('新建开机广告');   
        $('.newStart').addClass('current');
        $('.adManage').removeClass('current');
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
  var details = {cityCode:data.cityCode,adShape:4,timeSlot:data.timeInterval};
  //广告标题
  $('#advertiseTitle').val(data.advertiseTitle);
  //$('.advertiseTitle').html('<input type="text" id="advertiseTitle" class="form-control" name="header" placeholder="30字符以内" maxlength="30" value="'+data.advertiseTitle+'">');
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
  //品牌名称
  $('#brandName').val(data.brandName);
  //广告链接
  $('.filename').html(data.filename);
  $('#sponsorUrl').val(data.sponsorUrl);
  $('#userfileURL').val(data.sponsorUrl);
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
  $('#nationwide').attr('checked',true);
  //选择时间
  $('#Unlimited').attr('checked',true);
  //品牌名称
  $('#brandName').val();
  //广告链接
  $('.filename').html('');
  $('#sponsorUrl').val();
  //备注
  $('#remark').val();
  //图片
  $('#logoImg').attr('src','../../public/images/nopic.jpg');
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
        $('#adShapePrice').val(adShapePrice[2].price);
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
    adShape:4,
    timeSlot:timeInterval,
  }
  getPriceList(details);
});

/*判断上传图标*/
$doc.on('change','#logo',function(){
  var obj = document.getElementById('logo'),
      filetypes = ['.jpg','.png','.jpeg'],
      error = 'logoError',
      size = 5*1024;
   getPhotoSize(obj,filetypes,error,size);
})

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

/*预览真实效果*/
$doc.on('click','.realEffect',function(){
  $('.startNme').html($('#brandName').val());
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
  if($('#sponsorUrl').val()=='') {
    $('.documentError').html('请上传文件');
    return false;
  };
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
      adShape:4,
      adStatus:0,
      advertiseTitle:$('#advertiseTitle').val(),
      cityCode:$('#cityCodeList').val(),
      timeType:$('input[name=genre]:checked').val(),
      timeInterval:timeInterval,
      brandName:$('#brandName').val(),
      remark:$('#remark').val(),
      maxPrice:$('#maxPrice').text(),
      minPrice:$('#minPrice').text()
    }
  }else{
    var data={
      userID:$('#userID').val(),
      adID: $('#adID').val(),
      adShape:4,
      adStatus:0,
      advertiseTitle:$('#advertiseTitle').val(),
      cityCode:$('#cityCodeList').val(),
      timeType:$('input[name=genre]:checked').val(),
      timeInterval:timeInterval,
      brandName:$('#brandName').val(),
      remark:$('#remark').val(),
      logoURL:$('#logoURL').val(),
      filename:$('.filename').html(),
      sponsorUrl:$('#userfileURL').val(),
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

/*判断上传文件大小*/
$doc.on('change','#userfile',function(){
  var obj = document.getElementById('userfile'),
      filetypes = ['.zip'],
      error = 'documentError',
      size = 2*1024;
   getPhotoSize(obj,filetypes,error,size);
});
/*上传文件*/
$('#userfile').fileupload({
  url: '../Register/uploadFile',
  autoUpload: true, //是否自动上传  
  type: "post",
  maxFileSize: 2048,
  acceptFileTypes: /(\.|\/)(zip)$/i,
  dataType: "json"
}).on('fileuploadprogressall',function(data){
    $('#userfile').attr('disabled',true);
    $('#query_hint').css('display','block');
}).on('fileuploaddone', function (e,data){
  $('#query_hint').css('display','none');
  console.log(data.files[0].name);
  if (data.result.ERRORCODE == "0") {
    $('#userfile').attr('disabled',false);
    $('.filename').html(data.files[0].name);
    $('#userfileURL').val(data.result.RESULT.url);
    $('#sponsorUrl').val(data.result.RESULT.url);
    $('.documentError').html('');
  }else{
    $('#userfile').attr('disabled',false);
    $('#query_hint').css('display','none');
    $('.documentError').html('上传文件错误');
  }
})

