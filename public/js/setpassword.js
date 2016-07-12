/* 
* @Author: zhouyan
* @Date:   2015-10-28 09:56:17
* @Last Modified by:   zhouyan
* @Last Modified time: 2015-12-10 18:47:01
*/
$(function(){
	
	//表单验证
    $('#setpswForm').validate({
            //表单验证规则
            rules: {
                password: {
	                required: true,
	                rangelength:[6,10],
            	},
            	confirm_password: {
	                required: true,
	                rangelength:[6,10],
	                equalTo: '#password'
            	}
            },
            //错误提示
            messages: {
                password: {
	                rangelength: '请输入6-10个字符',
	                required: '请输入密码'
	            },
	            confirm_password: {
	                required: '请输入确认密码',
	                rangelength: '请输入6-10个字符',
	                equalTo: '两次输入密码不一致不一致'
	            }
             },
            //错误提示添加的位置
            errorPlacement: function(error, element) {   
                error.insertAfter ( element );
            },
            //全部填写正确后执行
            submitHandler: function() {
              var data = {
              	newpassword:$('#confirm_password').val(),
              	token:$('#token').val()
              }
              console.log(data);
              $.ajax({
              	url: 'updatePwd',
              	type: 'post',
              	dataType: 'json',
              	data:data,
              	success:function(data){
              		if (data.ERRORCODE == '0') {
              			window.location.href="accomplish";
              		} else{
              			$('#failed').show();
              		};
              	},
              	error:function(){
              		$('#failed').show();
              	}
              })
              
            }
    });
})