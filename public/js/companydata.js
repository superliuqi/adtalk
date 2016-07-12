/* 
* @Author: zhouyan
* @Date:   2015-10-30 16:40:44
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-16 18:01:39
*/
//获取用户信息
var $doc = $(document),chinaDistrict;
var $phoneNumber = $('#phoneNumber'),
	$codeError = $('.codeError');
 var codeError = $('<label class="error" id="inputCode-error" for="inputCode">验证码错误</label>'),checkForm=false;   //change 只使用了一次 不应该放在这里
$(function (){
	getUserDetailInfo();
    //表单验证开始
    checkForm = $('#messageForm').validate({
        rules: {
            companyName: {
                required: true,
                minlength: 6
            },
            licenseNumber: {
                required: true,
                minlength: 6
            },
            orgCode: {
                required: true,
                minlength: 8
            },
            registrationNumber: {
                required: true,
                minlength: 5
            },
            companyType: {
                required: true,
                min:0
            },
            city: {
                required: true
            },
            companyRegAdd: {
                required: true
            },
            companyPhone: {
                required: true,
                minlength: 8
            },
            name: {
                required: true,
                minlength: 2
            },
            IDNumber: {
                required: true,
                minlength: 18,
                isIDCard: true
            },
            telephone: {
                required: true,
                minlength: 11,
                isTelephone:true
            },
            code: {
                required: true,
                minlength: 4,
            },
            prove: {
                required: true
            },
            trcPic: {
                required: true
            }
            /*cocPic: {
                required: true
            },
            brPic: {
                required: true
            }*/
        },
        messages: {
            companyName: {
                required: '请输入公司全称',
                minlength: '请输入公司全称,最少六位'
            },
            licenseNumber: {
                required: '请输入公司营业执照注册号',
                minlength: '请输入规范的营业执照注册号'
            },
            orgCode: {
                required: '请输入公司组织机构代码证号',
                minlength: '请输入规范的组织机构代码证号'
            },
            registrationNumber: {
                required: '请输入公司税务登记证号',
                minlength: '请输入规范的税务登记证号'
            },
            companyType: {
                required: '请选择行业类别',
                min: '请选择行业类别'
            },
            city: {
                required: '请选择省份城市'
            },
            companyRegAdd: {
                required: '请输入公司详细地址'
            },
            companyPhone: {
                required: '请输入公司电话号码',
                minlength: '请输入正确的电话号码'
            },
            name: {
                required: '请输入运营者姓名',
                minlength: '请输入正确的运营者姓名'
            },
            IDNumber: {
                required: '请输入运营者身份证号码',
                minlength: '请输入正确的运营者身份证号码'
            },
            telephone: {
                required: '请输入运营者手机号码',
                minlength: '请输入正确的运营者手机号码',
                isTelephone:'手机格式不正确'
            },
            code: {
                required: '请输入验证码',
                minlength: '验证码输入错误'
            },
            prove: {
                required: '请上传授权证明'
            },
            trcPic: {
                required: '请上传税务登记证'
            },
            cocPic: {
                required: '请上传组织结构代码证'
            },
            brPic: {
                required: '请上传企业工商营业执照'
            }
        },
        //错误提示显示位置
        errorPlacement: function(error, element) {
            if (element.is(':radio'))
                error.appendTo(element.parent().next().next());
            else if (element.is('#phoneNumber')) {
                error.insertAfter(element.next());
            } else if (element.is('#city')) {
                error.insertAfter(element.parent().prev().children('select#province'));
            } else
                error.insertAfter(element);
        },
    });
});
function getUserDetailInfo(){
	$.ajax({
		url: 'getEnterpriseInfo',
		type: 'POST',
		dataType: 'json',
		data: {userID:$('#userID').val()},
	}).done(function(data) {
		if(data.ERRORCODE=="0"){
			if(data.RESULT.checkStatus=="0" || data.RESULT.checkStatus=="3"){
				editDetail(data.RESULT);
			}else{
				showDetail(data.RESULT);
			}
		}else{
			editDetail(data.RESULT);
		}
	});
}
function editDetail(info){
	$('#companyName').before('<input type="text" class="form-control" name="companyName" value="'+info.companyName+'">');
	$('#licenseNumber').before('<input type="text" class="form-control" name="licenseNumber" value="'+info.licenseNumber+'">');
	$('#orgCode').before('<input type="text" class="form-control" name="orgCode" value="'+info.orgCode+'">');
	$('#registrationNumber').before('<input type="text" class="form-control" name="registrationNumber" value="'+info.registrationNumber+'">');
	$('#companyAddress').before('<input type="text" class="form-control" name="companyAddress" value="'+info.companyAddress+'">');
	$('#companyPhone').before('<input type="text" class="form-control" name="companyPhone" value="'+info.companyPhone+'">');
	$('#operators'). before('<input type="text" class="form-control" id="operators" value="'+info.name+'" name="name">');
	$('#IDnumber').before('<input type="text" class="form-control" id="IDnumber" name="IDNumber" value="'+info.IDNumber+'">');
	$('#phoneNumber').before('<input type="text" class="form-control" id="phoneNumber" value="'+info.telephone+'" name="telephone"><input type="button" class="hidden" id="phoneCodebtn" value="获取验证码">');
	$('#brPic').attr('src',info.licenseScanPreview).removeClass('hidden').prev('input[type="hidden"]').val(info.licenseScanPreview);  
	$('#cocPic').attr('src',info.orgScanPreview).removeClass('hidden').prev('input[type="hidden"]').val(info.orgScanPreview);
    $('#trcPic').attr('src',info.taxRegistrationPreview).removeClass('hidden').prev('input[type="hidden"]').val(info.taxRegistrationPreview); 
	$('#prove').attr('src',info.impowerProve).removeClass('hidden').prev('input[type="hidden"]').val(info.impowerProve);
	$('.prove-file').removeClass('hidden');
	getTradeList(info.companyType);
	getCityList(info.cityCode);
	if(info.checkStatus=="0"  || info.checkStatus=="2"){
		$('.notice').html('');		
	}else if(info.checkStatus=="1"){
		$('.notice').text('您的企业资料正在审核中...');
	}else{
		$('.notice').text('您的企业资料未通过审核，原因是：'+info.reason);
	}
	$('#submitBtn').prop('disabled',false).val('提交审核');
}
 //行业类别
function getTradeList(companyType){
    $.getJSON('../../public/js/trade.json', function(data) {
        var body = {data: data};            
        var option = '<select name="companyType" id="trade" class="col-sm-12 form-control">{@each data as it,index}<option value="${index}">${it}</option>{@/each}</select>';
        $('#companyType').before(juicer(option, body));
        $('#trade').children('option:nth-child('+(companyType+1)+')').prop('selected','selected');
    });
}
//选择省、市开始
function getCityList(cityCode) {
    $.getJSON('../../public/js/chinaDistrict.json', function(data) {
        var body = {
            data: data
        }; //获取省区
        chinaDistrict = data;
        var option = '<div class="col-sm-6"><select class="form-control" id="province" name="province">{@each data as it}<option value="${it.code}">${it.name}</option>{@/each}</select></div>';
       	option +='<div class="col-sm-6"><select id="city" class="form-control" name="city"></select></div>';
        $('#city').before(juicer(option, body));
        var provinceCode = cityCode.slice(0,2)+'0000';
        $('#province').children('option[value='+provinceCode+']').prop('selected','selected');
        var cityList;
        for (var i=0; i < data.length; i++) {
	        if (data[i].code == provinceCode) {
	            cityList = data[i].list;
	        }
	    }
	    var body = {
	        data: cityList
	    };
        var city = '{@each data as it}<option value="${it.code}">${it.name}</option>{@/each}';
		$('#city').html(juicer(city, body));
		$('#city').children('option[value='+cityCode+']').prop('selected','selected');
    });
}
//正则表达式验证手机号码格式
$.validator.addMethod('isTelephone', function(value, element) {
    var reg = /^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/;
    return reg.test(value);

}, '手机格式不正确');

//手机获取验证码
var wait = 60;
var timer;
//获取验证按钮倒计时
function time(o) {
    if (wait == 0) {
        clearTimeout(timer);
        o.removeAttribute("disabled");
        o.value = "获取验证码";
        wait = 10;
        $codeError.hide();
    } else {
        o.setAttribute("disabled", true);   //change 使用了jquery为何还要使用setAttribute
        o.value = "重新发送(" + wait + ")";
        timer = setTimeout(function() {
                wait--;
                time(o)
            },1000)
    };
}
$doc.on('click','#phoneCodebtn', function(event) {
    var getphone = $('#phoneNumber').val();
    //判断是否是手机格式然后发送ajax请求
    if(!/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/.test(getphone)){
        return false;
    }
    if (getphone.length === 11) {    // 可只判断!==11的情况 美化代码 应使用===严格相等或!==严格不等来判断
        clearTimeout(timer);
        time(this);
        $.ajax({
            url: '../Register/getPhoneAuthCode',
            type: 'post',
            dataType: 'json',
            data: {
                phone: getphone
            },
            success: function(data){
                if (data.ERRORCODE == '0') {
                    //coding
                } else{
                    $codeError.show();
                };
            }
        })
    } else {
        // $phoneNumber.focus();  //change 去除
    };
});
//验证验证码
$doc.on('blur','#code', function() {
    var codeValue = $(this).val();
    if (codeValue.length == 4) {    //change 可只判断!==4的情况 美化代码 应使用===严格相等或!==严格不等来判断
        $.ajax({
            url: '../Register/validatePhoneCode',
            type: 'post',
            dataType: 'json',
            data: {
                phonecode: codeValue,
                phone:$phoneNumber.val()
            },
            success:function(data){
                if (data.ERRORCODE == '0') {
                   $('.ok').css('display', 'inline-block');
                   $('#submitBtn').attr('disabled',false).removeClass('disabled'); 
                } else{
                    //change ERRORCODE存在多种可能性
                    codeError.insertAfter ('#code');
                    $('#inputCode-error').css('display', 'inline-block');
                    $('#registersBtn').attr('disabled',false).removeClass('disabled');
                };
            },
            error:function(){
                alert('请稍后再试');
            }
        });      
        
    } else{
        //coding
        // codeError.insertAfter ('#code');
    };
    
});
$doc.on('change', '#province', function() {
    var cityCode = $(this).val(),
        i = 0,
        len = chinaDistrict.length;
    if (cityCode == "") {
        $city.html('<option value="">所属城市</option>');
        return;
    }
    for (; i < len; i++) {
        if (chinaDistrict[i].code == cityCode) {
            cityList = chinaDistrict[i].list;
        }
    }
    var body = {
        data: cityList
    };
    var option = '{@each data as it}<option value="${it.code}">${it.name}</option>{@/each}';
    $('#city').html(juicer(option, body));
});
function showDetail(info){
	$('#companyName').text(info.companyName);
	$('#licenseNumber').text(info.licenseNumber);
	$('#orgCode').text(info.orgCode);
	$('#registrationNumber').text(info.registrationNumber);
	$('.city').text(info.cityCode);
	$('#companyAddress').text(info.companyAddress);
	$('#companyPhone').text(info.companyPhone);
	$('#operators').text(info.name);
	$('#IDnumber').text(info.IDNumber);
	$('#phoneNumber').text(info.telephone);
	$('#brPic').attr('src',info.licenseScanPreview).removeClass('hidden').prev('input[type="hidden"]').val(info.licenseScanPreview);  
    $('#cocPic').attr('src',info.orgScanPreview).removeClass('hidden').prev('input[type="hidden"]').val(info.orgScanPreview);
    $('#trcPic').attr('src',info.taxRegistrationPreview).removeClass('hidden').prev('input[type="hidden"]').val(info.taxRegistrationPreview); 
    $('#prove').attr('src',info.impowerProve).removeClass('hidden').prev('input[type="hidden"]').val(info.impowerProve);
    $('.fileSelect').addClass('hidden');
    if(info.checkStatus=="0"  || info.checkStatus=="2"){
        $('.notice').html('');      
    }else if(info.checkStatus=="1"){
        $('.notice').text('您的企业资料正在审核中...');
    }else{
        $('.notice').text('您的企业资料未通过审核，原因是：'+info.reason);
    }
	$.getJSON('../../public/js/trade.json', function(data) {
        $('#companyType').text(data[info.companyType]);
    });
	$.getJSON('../../public/js/chinaDistrict.json', function(data) {
        var provinceCode = info.cityCode.slice(0,2)+'0000',cityList;
        for (var i=0; i < data.length; i++) {
	        if (data[i].code == provinceCode) {
	            cityList = data[i].list;
	        }
	    }
	    for (var i = 0; i < cityList.length; i++) {
	    	if(cityList[i].code== info.cityCode){
	    		$('#city').text(cityList[i].name);
	    	}
	    };
    });
}
//判断修改手机号显示按钮
$doc.on('change','#phoneNumber',function (){
    $('#phoneCodebtn').removeClass('hidden');
    $('.phone-code').removeClass('hidden');
});
$doc.on('click', '#submitBtn',function(event){
    if(checkForm.form()){
        $('form').submit();
        return true;
    }
    event.preventDefault();
});