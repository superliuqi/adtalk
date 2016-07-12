/* 
* @Author: wanghuilin
* @Date:   2016-04-07
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-04-18
*/

//声明变量
var base_URL = '/adtalk';
var confirmInfo = false,checkForm,$doc=$(document);
var timeInterval="";

$(function(){
  //表单验证开始
  checkForm = $('#accidForm').validate({      
      rules: {
          sponsorName: {
              required: true,
          },
          city: {
              required: true,
          },
          sponsorLogo: {
              required: true,
          },
          adurl: {
            required: true,
          }
      }, 
      messages: {
          sponsorName: {
              required: '请填写赞助商名称'
          },
          city: {
              required: '请选择省份城市'
          },
          sponsorLogo: {
              required: '请上传赞助商图标(支持jpg、jpeg、png格式的文件)'
          },
          adurl: {
            required: '请输入广告URL'
          }
      },
      errorPlacement: function(error, element) {
          if (element.is(':radio'))
              error.appendTo(element.parent().next().next());
          else if (element.is('#phoneNumber')) {
              error.insertAfter(element.next());
          } else if (element.is('#city')) {
              error.insertAfter(element.parent().prev().children('select#province'));
          } else
              error.insertAfter(element);
      }
  });
   //剪裁图片
  var jcrop_api,
      boundx,
      boundy,
      preview = $('#preview-pane'),
      xsize = $('#preview-pane .preview-container').width(),
      ysize = $('#preview-pane .preview-container').height(),
      pimg = $('#target'),
      LogoImgWidth= $('#sponsorLogoImg').width(),
      LogoImgHeight = $('#sponsorLogoImg').height(),
      x = $('#x1').val(),
      y = $('#y1').val(),
      w = $('#w').val(),
      h = $('#h').val();
  $('#sponsorLogoImg').Jcrop({
    onChange: updatePreview,
    onSelect: showCoords,
    //onRelease: clearCoords,
    aspectRatio: xsize / ysize
  },function(){
    var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
    jcrop_api = this;
    preview.appendTo(jcrop_api.ui.holder);
    console.log(this);
  });
  function updatePreview(c){
    if (parseInt(c.w) > 0){
      var rx = xsize / c.w;
      var ry = ysize / c.h;
      pimg.css({
        width: Math.round(rx * boundx) + 'px',
        height: Math.round(ry * boundy) + 'px',
        marginLeft: '-' + Math.round(rx * c.x) + 'px',
        marginTop: '-' + Math.round(ry * c.y) + 'px'
      });
    }
  };
  checkPerfectInfo();//检查是否完善资料
});

//意外险广告
//上传图片

$doc.on('change','.fileSelect',function (){
  var reader = new FileReader(),$this=$(this);
  reader.readAsDataURL(this.files[0]);
  var size = this.files[0].size;
  console.log(size);
  reader.onload = function(e){
    var data = {
        size:size,
        image:e.target.result,
    };
    $('#sponsorLogoURL').val(e.target.result);
    $('#sponsorLogoImg').attr('src',e.target.result);
    $('#target').attr('src',e.target.result);
    $('.jcrop-holder img').attr('src',e.target.result);
    /*$.ajax({
        url: '../Register/uploadImage',
        type: 'POST',
        async:false,
        dataType: 'json',
        data:data
    }).done(function(data){
      if(data.ERRORCODE=="0"){
        $('#target').attr('src',data.RESULT.url);
        $('#sponsorLogoImg').attr('src',data.RESULT.url);
        //$('#sponsorLogoURL').val(data.RESULT.url);
      }else{
        console.log('uploadImage error')
      }
    });*/
  }
});
//剪裁图片
function cut(){
  var image = new Image();
  image.src = $('#sponsorLogoImg').attr("src");
  image.onload = function() {
    var LogoImgWidth= image.width/244,
        LogoImgHeight = image.height/244, 
        x1 = $('#x1').val()*LogoImgWidth,
        y1 = $('#y1').val()*LogoImgHeight,
        w = $('#w').val()*LogoImgWidth,
        h = $('#h').val()*LogoImgHeight;
    console.log(image.width,image.height);   
    console.log(LogoImgWidth,LogoImgHeight);
    console.log(x1,y1,w,h);
    var data = {
        x1:x1,
        y1:y1,
        w:w,
        h:h,
        url:$('#sponsorLogoURL').val()
    };
    $.ajax({
      url: '../Register/imageCut',
      type: 'POST',
      dataType: 'json',
      data: data
    }).done(function(data) {
      if(data.ERRORCODE=="0"){
        $('#sponsorLogoImg').attr('src',data.RESULT.url);
        pimg = $('#target'),
        pimg.css({
          width: 200 + 'px',
          height: 200 + 'px',
          marginLeft: 0 + 'px',
          marginTop: 0 + 'px'
        });
        $('#target').attr('src',data.RESULT.url);
        $('#sponsorLogoURL').val(data.RESULT.url);
        console.log(data.RESULT.url);
      }
      console.log("success");
    }).fail(function() {
      console.log("error");
    });
  }
};
function showCoords(c){
  $('#x1').val(c.x);
  $('#y1').val(c.y);
  $('#w').val(c.w);
  $('#h').val(c.h);
};

function clearCoords(){
  $('#coords input').val('');
};

//投放地区
$doc.on('click','#aiNationwide',function(){
  $('#cityCodeList').val(1);
  $('#citymin').val(0);
  $('#citymax').val(0);
  $('.choose').html('你选择的地区有：全国');
}); 
//获取json数据,显示地区
var jsondata=[];
(function getJson(){
  $.getJSON( '../../public/js/china.json',function(data){
    jsondata=data;
    var i=0,len=data.length-1,a='',b='';c='';
    for (i;i<len;i++) {
      a+='<label><input type="checkbox" data-price="10" data-count="'+data[i]['list'].length+'" value="'+data[i]['areaCode']+'">'+data[i]['areaName']+'</label>'
      b+='<div class="quyu'+i+'">';
      for (var j=0;j<data[i]['list'].length;j++) {
        c='';
        for (var k=0;k<data[i]['list'][j]['list'].length;k++) {
          c+='<label><input type="checkbox" data-price="2" data-value="'+data[i]['list'][j]['list'][k]['code']+'" value="'+data[i]['list'][j]['list'][k]['name']+'" name="city"></input>'+data[i]['list'][j]['list'][k]['name']+'</label>'
        };
      b+='<div class="province"><input type="checkbox" data-price="5" data-count="'+data[i]['list'][j]['list'].length+'" data-value="'+data[i]['list'][j]['code']+'" value="'+data[i]['list'][j]['name']+'"><span class="more">'+data[i]['list'][j]['name']+'</span><div class="city">'+c+'<div class="off">确定</div></div></div>'
      };
      b+='</div>';
    };
    $('.area').append(a);
    $('.provincearea').append(b);

  })
}());
$doc.on('click', '.more', function() {
  $('.city').removeClass('current');
  $(this).siblings('.city').addClass('current');
}); 
//选择地区
$doc.on('click', '.area input', function() {
  var index=parseInt($(this).val())-1;
  var aresArr= $(this).parent().parent().siblings().eq(1).children().eq(index).find('input');
  if(this.checked){
    aresArr.each(function(index, el) {
      el.checked=true
    });
  }else{
    aresArr.each(function(index, el) {
      el.checked=false;
    });
  }
});
//省份选择
$doc.on('click', '.province input', function() {
  var citys=$(this).siblings('.city').children().find('input');
  if(this.checked){
    citys.each(function(index, el) {
      el.checked=true;
    });
  }else{
    citys.each(function(index, el) {
      el.checked=false;
    });
  }
  var provinceName = $(this).val();
  $(this).next('.more').text(provinceName+'('+citys.length+')');

}); 
//城市选择
$doc.on('click','.city input', function(){
  var province = $(this).parents('.city').parents('.province');
  if(!this.checked){
    province.children('input').prop('checked',false);
  }
  var chooseLen = $('.city.current').children('label').children('input:checked').length;
  var provinceName = province.children('input').val();
  province.children('.more').text(provinceName+'('+chooseLen+')');
});

$doc.on('click', '#modalBtn', function(event) {
  var all=$('.province input:checked'),cityCode='';
  $('.choose').html('你选择的地区有：');
  for(var i=0;i<all.length;i++){
    $('.choose').append($(all[i]).val()+'、');
    cityCode+=$(all[i]).attr('data-value')+'|';
    $('.dn').show();
  }
  $('#cityCodeList').val(cityCode);
});
$doc.on('click', '#world', function(event) {
  $('.dn').hide();
  $('.province input').attr('checked',false); 
  $('.choose span').html('');
}); 
$doc.on('click', '.off', function(event) {
  $('.city').removeClass('current');
});

$doc.on('click', '#tailedAdvert', function(event) {
  $('.tailedAdvert').show();
  $('.headAdvert').hide();
});
$doc.on('click', '#headAdvert', function(event) {
  $('.headAdvert').show();
  $('.tailedAdvert').hide();
});
//选择地区结束
//检查是否完善资料
function checkPerfectInfo(){
  $.ajax({
    url: '../Register/checkPerfectInfo',
    type: 'POST',
    dataType: 'json',
    data: {userID:$('#userID').val()},
    success:function(data){
      if (data.ERRORCODE == '0') {
        $('.notice').remove();
      }else{
        $('#submit').prop('disabled',true);
        $('#save').prop('disabled',true);
      };
    },
    error:function(){
      console.log('error');
    }
  });
}
function judge(){
  var sponsorLogo = $('#sponsorLogo').val();
  if(!/\.(jpg|jpeg|png|JPG|PNG)$/.test(sponsorLogo)){
    $('#sponsorLogo-error').html("支持jpg、jpeg、png格式的文件，大小不能超过5M");
    return false;
  }
}
//点击审核提交按钮
$doc.on('click', '#submit',function(){
  judge();
  $('#submit').attr('disabled','disabled');
  var image = new Image();
      image.src = $('#sponsorLogoImg').attr("src");
  var LogoImgWidth= image.width/244;
      LogoImgHeight = image.height/244, 
      x1 = $('#x1').val()*LogoImgWidth,
      y1 = $('#y1').val()*LogoImgHeight,
      w = $('#w').val()*LogoImgWidth,
      h = $('#h').val()*LogoImgHeight;
  /*if (w != 0 || h != 0) {*/
    var image = new Image();
    image.src = $('#sponsorLogoImg').attr("src");
    image.onload = function() {
      if (w == 0 || h == 0) {
        var data1 = {
              x1:0,
              y1:0,
              w:image.width,
              h:image.height,
              url:$('#sponsorLogoURL').val()
          };
      }else{
        var data1 = {
          x1:x1,
          y1:y1,
          w:w,
          h:h,
          url:$('#sponsorLogoURL').val()
        };
      }
      $.ajax({
        url: '../Register/imageCut',
        type: 'POST',
        dataType: 'json',
        data: data1
      }).done(function(data) {
        if(data.ERRORCODE=="0"){
          $('#sponsorLogoURL').val(data.RESULT.url);
          if(checkForm.form()){
            sumbitinfo();
            return true;
          }
          console.log(data.RESULT.url);
        }
        console.log("success");
      }).fail(function() {
        console.log("error");
      });
    }
  /*else{
    if(checkForm.form()){
      sumbitinfo();
      return true;
    }*/
  //}
  
});
function sumbitinfo(btn,types){
  var data2={
    userID:$('#userID').val(),
    sponsor:$('#sponsor').val(),
    cityCode:$('#cityCodeList').val(),
    sponsorLogo:$('#sponsorLogoURL').val(),
    adurl:$('#adurl').val(),
    remark:$('#remark').val()
  }
  $.ajax({
    url: 'addAccidInsurance',
    type: 'POST',
    dataType: 'json',
    data:data2,
    success:function(data){
      if(data.ERRORCODE=="0"){
        location.reload();
      }else{
        console.log('error');
      }
    }
  })
}