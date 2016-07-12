/* 
* @Author: zhouyan
* @Date:   2015-09-25 16:55:12
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-29 16:41:23
*/
$(function() {

	//声明变量
	var $email = $('#email'),$doc=$(document);

	//email获得焦点时提示隐藏；
	$email.on('focus',function(event) {
    	$('.remove').hide();
    	$('.glyphicon-ok').hide();
    	$('.null-error').hide();
    	$('.email-false').hide();
    	$('.login-error').hide();
    });

    //email输入框失去焦点时发送ajax请求
    $email.on('blur',function() {
    	var emailValue = $email.val();
		if(!/^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/.test(emailValue)){
			$('.email-false').show();
			return false;
		}
		$.ajax({
			url: '../Register/validateLoginEmail',
			type: 'POST',
			dataType: 'json',
			data: {email: emailValue},
			success: function(data){
				if (data.ERRORCODE == '0') {
					$('.glyphicon-ok').css('display', 'inline-block');
					$('#loginBtn').attr('disabled',false).removeClass('disabled');
				} else{
					$('.remove').css('display', 'inline-block');
					$('#loginBtn').attr('disabled',true).addClass('disabled');
				};
			},
			error: function(){
				alert('请稍后重试');
			}
		})		
    });

	//点击下一步登录
	$doc.on('click', '#loginBtn', function(event) {
		toLogin();
	});
	$doc.on('keypress',function (e){
		if(e.keyCode==13){
			toLogin();
		}
	})
	function toLogin(){
		var emailValue = $email.val(),
			passwordValue = $('#password').val();

		if (emailValue == '' || passwordValue == '') {
			$('.null-error').show();
			return false;			
		};

		$.ajax({
			url: '../Register/doLogin',
			type: 'POST',
			dataType: 'json',
			data: {
				email:emailValue,
				password:passwordValue
			},
			success:function(data){
				if (data.ERRORCODE == '0') {
					window.location.href="../Workbench/workbench";
				}else if(data.ERRORCODE =="1006"){
					$('.login-error').show();
				}else{
					$('.remove').css('display', 'inline-block');
				};
			},
			error:function(){

			}
		})
	}
	$.get('../Register/getPlayCount',function (data){
		data = JSON.parse(data);
		new FlipClock($('.clock'), data.RESULT,{
			clockFace: 'Counter',
			autoStart: true,
			minimumDigits: data.RESULT.length
		});
		$('.clock').append('<h4 class="text-muted">人次收听</h4>')
	});
	
});