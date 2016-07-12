/* 
* @Author: zhouyan
* @Date:   2015-09-23 10:48:03
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-06 15:55:06
*/
$(function() {

	//声明变量
	var $email = $('#email'),
		$possword = $('#password'),
		$inputCode = $('#inputCode');
	var codeError = $('<label class="error" id="inputCode-error" for="inputCode">验证码错误</label>')


	//加载时验证码自动换一张
	changeCode();

	//获取验证码
	function changeCode(inputCode){
		$.ajax({
		  url: "getAuthCode",
		  type:'post',
		  dataType:'json',
		  success: function(data){
		  	$('img').attr("src",data.RESULT);
		  },
		  error:function(){
		  	alert('请稍后重试');
		  }
		});
	}

	//点击换验证码
	$('#changeCode').on('click', changeCode);

	//获取焦点时，提示隐藏
	$email.on('focus', function(){
		$('.por span').hide();
		$('.por .glyphicon-remove').hide();
	});

	//阅读同意
	$(document).on('click', '#agree', function(event) {
		$('#agreement').attr("checked", true);
	});

	//失去焦点验证邮箱格式、邮箱是否注册
	$email.on('blur', function(){
		var emailValue = $email.val();
		//正则表达式，验证是否为邮箱格式
		if(!/^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/.test(emailValue)){
			return false;
		}
		$.ajax({
			url: 'validateEmail',
			type: 'POST',
			dataType: 'json',
			data: {email: emailValue},
			success: function(data){
				if (data.ERRORCODE == '0') {
					$('.por .glyphicon-ok').css('display', 'inline-block');
					$('#registersBtn').attr('disabled',false).removeClass('disabled');
				} else{
					$('.por .glyphicon-remove').css('display', 'inline-block');
					$('#registersBtn').attr('disabled',false).addClass('disabled');
				};
			},
			error: function(){
				alert('请稍后重试');
			},
		})		
	})

	//失去焦点发送ajax验证验证码是否正确
	$inputCode.on('blur',function(event) {
		$.ajax({
			url: 'validateCode',
			type: 'post',
			dataType: 'json',
			data: {code:$inputCode.val()},	//change #inputCode应改为this
			success:function(data){
				if (data.ERRORCODE == '0') {
					$('#registersBtn').attr('disabled',false).removeClass('disabled');
				} else{
					codeError.insertAfter('#inputCode');
					$('#registersBtn').attr('disabled',true).addClass('disabled');
				};
			},
			error:function(){
				codeError.insertAfter('#inputCode');
			}
		})
	});

	//重新发送邮件
	$('#resend').on('click',function() {
		var data = {
				email: $email.val(),
				password: $possword.val()
			};
		$.ajax({
			url: 'sendEmail',
			type: 'POST',
			dataType: 'json',
			data:data,
			success:function(data){
				$('#resendSuccess').fadeIn('500', function() {
					$('#resendSuccess').delay(800).fadeOut('500');
				});
			},
			error:function(){
				alert('发送失败，请重试');
			}
		})
	});
	
	//表单验证
	$('#registerForm').validate({
		//表单验证规则
		rules: { 
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				rangelength:[6,10],
			},
			confirm_password: {
				required: true,
				rangelength:[6,10],
				equalTo: '#password'
			},
			agreement: {
				required: true
			},
			inputCode: {
				required: true
			}
		},
		//错误提示
		messages: {
			email: {
				required: '请输入Email地址',
				email: '请输入正确的Email地址'
			},
			password: {
				rangelength: '请输入6-10个字符',
				required: '请输入密码'
			},
			confirm_password: {
				required: '请输入确认密码',
				rangelength: '请输入6-10个字符',
				equalTo: '两次输入密码不一致不一致'
			},
			inputCode: {
				required: '请输入验证码'
			},
			agreement: {
				required: '请选择'
			}
		},
		//错误提示添加的位置
		errorPlacement: function(error, element) {   
		    if ( element.is(":radio") )
		        error.appendTo( element.parent().next().next() );
		    else if ( element.is(":checkbox") )
		        error.insertAfter ( element.parent() );
		    else
		    	error.insertAfter ( element );
		},
		//全部填写正确后执行
		submitHandler: function() {
			// 提交表单用户名和密码
			var data = {
				email: $email.val(),
				password: $possword.val()
			};
			$.ajax({
				url: 'sendEmail',
				type: 'POST',
				dataType: 'json',
				data:data,
				success:function(data){
					if (data.ERRORCODE == '0') {
						$('.wrap1').hide();
						$('.wrap2').show();
						$('#userID').html($email.val());
						$('.step li:eq(1)').addClass('current2').removeClass('bg').siblings('li').removeClass('current');
						$('#registersBtn').attr('disabled',false).removeClass('disabled');
					} else{
						if (data.ERRORCODE == '10009') {
							$email.focus();
						} else{
							alert('请稍后重试');
						};
					};
				},
				error:function(){
					alert('请稍后重试');
				}
			})
		}
	});
	//表单验证结束
	
});
