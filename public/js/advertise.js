/* 
* @Author: zhouyan
* @Date:   2015-10-08 11:44:07
* @Last Modified by:   wanghuilin
* @Last Modified time: 2016-05-20
*/

/*声明变量*/
var base_URL = '/adtalk';
var confirmInfo = false,checkForm,$doc=$(document);
var timeInterval="";
$(function(){
 /* 剪裁图片*/
  var jcrop_api,
      boundx,
      boundy,
      preview = $('.preview-pane'),
      xsize = $('.preview-pane .preview-container').width(),
      ysize = $('.preview-pane .preview-container').height(),
      pimg = $('.target'),
      LogoImgWidth= $('#logoImg').width(),
      LogoImgHeight = $('#logoImg').height(),
      x1 = $('#x1').val(),
      y1 = $('#y1').val(),
      w = $('#w').val(),
      h = $('#h').val();
      
  $('#logoImg').Jcrop({
    onChange: updatePreview,
    onSelect: showCoords,
    /*onRelease: clearCoords,*/
    setSelect: [ 2, 2,196, 196 ],
    aspectRatio: xsize / ysize
  },function(){
    var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
    jcrop_api = this;
    preview.appendTo(jcrop_api.ui.holder);
  });
  pimg.css({
        marginLeft: '0px',
        marginTop: '0px'
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
	countCityPrice();//计算价格
	checkPerfectInfo();//检查是否完善资料
});

/*上传图片*/
$doc.on('change','.fileSelect',function (){
  var reader = new FileReader(),$this=$(this);
  reader.readAsDataURL(this.files[0]);
  var size = this.files[0].size;
  reader.onload = function(e){
    var data = {
        size:size,
        image:e.target.result
    };
    $('#logoURL').val(e.target.result);
    $('#logoImg').attr('src',e.target.result);
    $('.target').attr('src',e.target.result);
    $('.previewLogo').attr('src',e.target.result);
    $('.jcrop-holder img').attr('src',e.target.result);
    $('#kanbanImg').attr('src',e.target.result);
  }
});

function showCoords(c){
  $('#x1').val(c.x);
  $('#y1').val(c.y);
  $('#x2').val(c.x2);
  $('#y2').val(c.y2);
  $('#w').val(c.w);
  $('#h').val(c.h);
};

function clearCoords(){
  $('#coords input').val('');
};

/*限制上传图片格式*/
function judge(){
  var logo = $('#logo').val();
  var logoURL = $('#logoURL').val();
  if(logoURL == ''){
    $('.logoError').html('请上传图标');
    return false;
  }else if(!/\.(jpg|jpeg|png|JPG|PNG)$/.test(logo)){
    $('.logoError').html("支持jpg、jpeg、png格式的文件");
    return false;
  }else{
    $('.logoError').html('');
  }
}

/*预览真是效果*/
function check(){
  var image = new Image(),
      LogoImgWidth= image.width/204,
      LogoImgHeight = image.height/204, 
      x1 = $('#x1').val()*LogoImgWidth,
      y1 = $('#y1').val()*LogoImgHeight,
      w = $('#w').val()*LogoImgWidth,
      h = $('#h').val()*LogoImgHeight;
  image.src = $('#logoImg').attr("src");
  image.onload = function() {
    if (w == 0 || h == 0) {
      var data1 = {x1:0,y1:0,w:image.width,h:image.height,url:$('#logoURL').val()}
    }else{
      var data1 = {x1:x1,y1:y1,w:w,h:h,url:$('#logoURL').val()}
    };
    if ($('#logoURL').val()=='') {
      $('.logoError').html('未上传图片');
      console.log('未上传图片');
      return false;
    };
    $.ajax({
      url: '../Register/imageCut',
      type: 'POST',
      dataType: 'json',
      data: data1,
      success:function(data) {
        if(data.ERRORCODE=="0"){
          $('.previewLogo').attr('src',data.RESULT.url);
          console.log("success");
        }
      },
      error:function() {
        console.log("error");
        $('.previewLogo').attr('src','../../public/images/nopic.jpg');
      }
    })
  }
}

/*遮罩层点击取消，遮罩层隐藏*/
$('#dialog a').on('click', function(){
    $("#fullbg").hide();   
    sumbitinfo();
});

/*计算价格*/
$doc.on('click','#time input',function(){
    GetCount();
});

/*切换城市*/
$doc.on('click','#nationwide',function(){
	$('#cityCodeList').val(1);
	$('#citymin').val(0);
	$('#citymax').val(0);
	$('.choose').html('你选择的地区有：全国');
	countCityPrice();
});
//选中复选框，相应价格+，取消价格—
function GetCount() {
  var conts = [];
  $('#time input[type=checkbox]:checked').each(function(){
      //conts = parseFloat(conts) + parseFloat($(this).data('price'));
      conts.push($(this).data('price'));
  });
  //console.log(conts);
  var ShapePrice = $('#adShapePrice').val();
  var timePriceMin = parseFloat(Math.min.apply(null,conts));
  var timePriceMax = parseFloat(Math.max.apply(null,conts));
  var cityPriceMin = $('#citymin').val();
 	var cityPriceMax = $('#citymax').val();
 	timePriceMin = timePriceMin== Infinity?0:timePriceMin;
 	timePriceMax = timePriceMax== -Infinity?0:timePriceMax;
  $('#timemin').val(timePriceMin);
  $('#timemax').val(timePriceMax);
	$('#minPrice').html(Number(parseFloat(ShapePrice)+timePriceMin+parseFloat(cityPriceMin)).toFixed(2));
	$('#maxPrice').html(Number(parseFloat(ShapePrice)+timePriceMax+parseFloat(cityPriceMax)).toFixed(2));
  console.log($('#minPrice').html(),$('#maxPrice').html());
}

/* 获取mp3文件的时间 兼容浏览器 */
function getTime() {
  setTimeout(function () {
    var duration = $("#audio")[0].duration;
    if(isNaN(duration)){
      getTime();
    }
    else{
      console.info("该音频的总时间为："+$("#audio")[0].duration+"秒")
    }
  }, 10);
}

/*把文件转换成可读URL*/
function getObjectURL(file) {
  var url = null;
  if (window.createObjectURL != undefined) { // basic
      url = window.createObjectURL(file);
  } else if (window.URL != undefined) { // mozilla(firefox)
      url = window.URL.createObjectURL(file);
  } else if (window.webkitURL != undefined) { // webkit or chrome
      url = window.webkitURL.createObjectURL(file);
  }
  return url;
}

/*限制上传格式和大小*/
function getPhotoSize(obj,filetypes,error,size){
  photoExt=obj.value.substr(obj.value.lastIndexOf(".")).toLowerCase();//获得文件后缀名
  console.log(photoExt);
  var isnext = false; 
  if(filetypes && filetypes.length>0){ 
    for(var i =0; i<filetypes.length;i++){ 
      if(filetypes[i]==photoExt){ 
        isnext = true; 
        break; 
      } 
    }
  }
  if(!isnext){ 
    $('.'+error).html("文件格式不正确，请重新上传!");
    obj.value =""; 
    return false; 
  }else{
    $('.'+error).html("");
  }
  var fileSize = 0;
  var isIE = /msie/i.test(navigator.userAgent) && !window.opera;            
  if (isIE && !obj.files) {          
    var filePath = obj.value;            
    var fileSystem = new ActiveXObject("Scripting.FileSystemObject"); 
    if(!fileSystem.FileExists(filePath)){ 
      $('.'+error).html("附件不存在，请重新输入！");
      return false; 
    }else{
     $('.'+error).html(" ");
    }  
    var file = fileSystem.GetFile (filePath);               
    fileSize = file.Size;         
  }else {  
    fileSize = obj.files[0].size;     
  } 
  fileSize=Math.round(fileSize/1024*100)/100; //单位为KB
  console.log(fileSize);
  if(fileSize>=size){
    $('.'+error).html("尺寸不符合，请重新上传!");
    return false;
  }else{
    $('.'+error).html(" ");
  }
  if(fileSize<=0){ 
    $('.'+error).html("文件大小不能为0M！");
    target.value =""; 
    return false; 
  }else{
    $('.'+error).html(" ");
  }
};

/*获取json数据,显示地区*/
var jsondata=[];
(function getJson(){
	$.getJSON( '../../public/js/china.json',function(data){
		jsondata=data;
		var i=0,len=data.length-1,a='',b='';c='';
		for (i;i<len;i++) {
			a+='<label><input class="areaChk" type="checkbox" data-price="10" data-count="'+data[i]['list'].length+'" value="'+data[i]['areaCode']+'">'+data[i]['areaName']+'</label>'
			b+='<div class="quyu'+i+'">';
			for (var j=0;j<data[i]['list'].length;j++) {
				c='';
				for (var k=0;k<data[i]['list'][j]['list'].length;k++) {
					c+='<label><input class="cityChk" type="checkbox" data-price="2" data-value="'+data[i]['list'][j]['list'][k]['code']+'" value="'+data[i]['list'][j]['list'][k]['name']+'"></input>'+data[i]['list'][j]['list'][k]['name']+'</label>'
				};
			b+='<div class="province"><input class="provinceChk" type="checkbox" data-price="5" data-count="'+data[i]['list'][j]['list'].length+'" data-value="'+data[i]['list'][j]['code']+'" value="'+data[i]['list'][j]['name']+'"><span class="more">'+data[i]['list'][j]['name']+'</span><div class="city">'+c+'<div class="off">确定</div></div></div>'
			};
			b+='</div>';
		};
		$('.area').append(a);
		$('.provincearea').append(b);
	})
}());

// 弹出层[一个省份的所有城市]
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
  selectArea();
});

function selectArea(){
	var all=$('.province input:checked'),cityCode='';
	$('.choose').html('你选择的地区有：');
	for(var i=0;i<all.length;i++){
		$('.choose').append($(all[i]).val()+'、');
		cityCode+=$(all[i]).attr('data-value')+'|';
		$('.dn').show();
	}
	$('#cityCodeList').val(cityCode);
}

$doc.on('click', '#world', function(event) {
	$('.dn').hide();
	$('.province input').attr('checked',false); 
	$('.choose span').html('');
}); 
$doc.on('click', '.off', function(event) {
	$('.city').removeClass('current');
});

//根据城市代码获取城市名字
function parseCityCode(cityCode){
  var city='';
  if(cityCode=="1"){
      return "全国";
  }
  $.ajax({
      url: '../admin/Advertise/getCityName',
      type: 'POST',
      async:false,
      dataType: 'json',
      data:{cityCode:cityCode}
  }).done(function(data){
    if(data.ERRORCODE=="0" && data.RESULT.length>0){
      var result = data.RESULT,len = result.length,i=0;
      for (; i < len; i++) {
        city+=result[i].cityName+' | ';
      };
    }else{
        city = '无数据';
        console.log(1);
    }
  }).fail(function() {
      city ='无数据';
      console.log(2);
  });
  return city;
};
/*时间段*/
$doc.on('change', 'input[name="genre"]', function() {
	$('input[name=time]').prop('checked',false);
	if($('input[name="genre"]:checked').val()=="1"){
		$('#timeTitle').removeClass('hidden');
		$('#timeTitle').next('dl').addClass('hidden');
		$('input[name=time]').prop('checked',true);
	}else{
		$('#time').removeClass('hidden');
		$('#time').prev('dl').addClass('hidden');
	}
});

/*查看价格明细详情*/
function getPriceList(details){
	//ajax请求
	$.ajax({
		url: './getPriceList',
		type: 'POST',
		dataType: 'json',
		data:details,
		success:function(data){
			if (data.ERRORCODE == '0') {
				//循环城市显示在页面上
				var cityPrice = '<dt>投放地区</dt>',
					cityLen = data.RESULT.cityCodePrice.length,
					cityCodePrice = data.RESULT.cityCodePrice;
				for (var i = 0; i < cityLen; i++) {
					cityPrice += '<dd><p class="float-l">'+cityCodePrice[i].cityName+'</p>';
					cityPrice +='<p class="float-r"><span>'+cityCodePrice[i].price+'</span>元/条</p></dd>';
				};
				$('#city').html(cityPrice||'<dd><p class="float-l">全国</p></dd>');

				//循环时间展示在页面上
				var $timePrice = '<dt>投放时间段</dt>',
					timeLen = data.RESULT.timeSlotPrice.length,
					$timeSlotPrice = data.RESULT.timeSlotPrice;
				for (var i = 0; i < timeLen; i++) {
					$timePrice += '<dd><p class="float-l">'+$timeSlotPrice[i].timeSlot+'</p>';
					$timePrice +='<p class="float-r"><span>'+$timeSlotPrice[i].price+'</span>元/条</p></dd>';
				};
				$('#timeSlot').html($timePrice);

				//展示广告形式
				var adShapePrice = data.RESULT.adShapePrice;
				$('#adtypePrice').html(adShapePrice.price);
				if (adShapePrice.adType == '1') {
					$('#adType').html('冠名广告');
				}else if(adShapePrice.adType == '2'){
					$('#adType').html('尾标广告');
				}else{
          $('#adType').html('开机广告');
        }
				var minPrice = $('#minPrice').html(),maxPrice = $('#maxPrice').html();
				$('.modal-footer span').html('价格区间：'+minPrice+'~ '+maxPrice+'元/条');
        console.log(minPrice,maxPrice);
			} else{
				console.log('error');
			};
		},
		error:function(){
			console.log('ajax请求失败');
		}
	})
};

/*获取选中地区的最高价格和最低价*/
$doc.on('click', '#modalBtn', function(event) {
	countCityPrice();		
});
function countCityPrice(){
	$.ajax({
		url: './comparePrice',
		type: 'POST',
		dataType: 'json',
		data: {cityCode:$('#cityCodeList').val()},
		success:function(data){
			if (data.ERRORCODE == '0') {
				$('#citymax').val(data.RESULT.maxPrice);
				$('#citymin').val(data.RESULT.minPrice);
				GetCount();
			} else{
				console.log('error');
			};
		},
		error:function(){

		}
	})
};

/*编辑状态*/
//取用户所选的投放地区
function cityDetail(data){
  var cityArr = data.cityCode.split('|');
  if (cityArr.length == 389) {
    $('.dn').hide();
    $('#nationwide').prop('checked',true);
    $('.showChoose').html('全国');
  }else{
    $('.dn').show();
    $('#partialArea').prop('checked',true);
    $('.showChoose').html(parseCityCode(data.cityCode));
    $.each(cityArr, function(n, v){
      $('[data-value="'+v+'"]').prop("checked",true);
    });
  }
};

//取用户所选的时间
function timeDetail(data){
  var timeArr = data.timeInterval.split('|');
  if (timeArr.length == 25) {
    $('#timeTitle').removeClass('hidden');
    $('#time').addClass('hidden');
    $('#Unlimited').prop('checked',true);
  }else{
    $('#timeTitle').addClass('hidden');
    $('#time').removeClass('hidden');
    $('#weekday').prop('checked',true);
    $('input[name=time]').prop('checked',false);
    $.each(timeArr, function(n, v){
      $('[data-value="'+v+'"]').prop("checked",true)
    });
    $('.chk').each(function(){
      var self = $(this);
      var selfVal = self.val();
      $.each(timeArr, function(n, v){
        if(v == selfVal){ 
          self.prop('checked',true);
        }
      });
    });
  }
};

/*剪切图并提交 -- 编辑*/
function editInfo(wsize,hsize,img,infoUrl){
  if ($('#logoURL').val()=='') {
    sumbitinfo(infoUrl);
  }else{
    imageCut(wsize,hsize,img,infoUrl);
  }
};

/*剪切图并提交 -- 新建*/
function referInfo(wsize,hsize,img,infoUrl){
  imageCut(wsize,hsize,img,infoUrl);
};

function imageCut(wsize,hsize,img,infoUrl){
 if (checkForm.form()) {
    var image = new Image(),
        LogoImgWidth= image.width/wsize,
        LogoImgHeight = image.height/hsize, 
        x1 = $('#x1').val()*LogoImgWidth,
        y1 = $('#y1').val()*LogoImgHeight,
        w = $('#w').val()*LogoImgWidth,
        h = $('#h').val()*LogoImgHeight;
    image.src = $('#'+img).attr("src");
    image.onload = function() {
      if (w == 0 || h == 0) {
        var data1 = {x1:0,y1:0,w:image.width,h:image.height,url:$('#logoURL').val()}
      }else{
        var data1 = {x1:x1,y1:y1,w:w,h:h,url:$('#logoURL').val()}
      };
      $.ajax({
        url: '../Register/imageCut',
        type: 'POST',
        dataType: 'json',
        data: data1,
        success:function(data) {
          $('#logoURL').val(data.RESULT.url);
          $('.previewLogo').val(data.RESULT.url);
          sumbitinfo(infoUrl);
          console.log("success");
        },
        error:function() {
          $('#submit').prop('disabled',false);
          $('#save').prop('disabled',false);
          console.log("error");
        }
      })
    }
  }
}

/*检查是否完善资料*/
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

