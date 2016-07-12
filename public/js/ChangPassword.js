$(function(){

    //声明全局变量
    var pswerror = $('.passworderror'),
        ajerror = $('.ajaxerror'),
        oldpsw = $('#old');


    //获得焦点后error2隐藏
    $(document).on('click', '#old', function() {
        pswerror.hide();
    });   

    //自定义函数showPage,密码修改成功后显示
    function showPage(){
        $('#fullbg').show();
        $('#dialog').show();
    }
    
    //表单验证
    $('#changePasswordForm').validate({
        //表单验证规则
        rules: {
            old: {
                required: true,
            },
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
            old: {
                required: '请输入当前的密码'
            },
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
            if ( element.is(":radio") )
                error.appendTo( element.parent().next().next() );
            else if ( element.is(":checkbox") )
                error.insertAfter ( element.parent() );
            else
                error.insertAfter ( element );
        },
        //全部填写正确后执行
        submitHandler: function() {
            var data = {
                userID: $('#userID').val(),
                oldPassword:oldpsw.val(),
                password: $('#password').val()
            };
            // 提交表单用户名和密码
            $.ajax({
                url: './editPwd',
                type: 'post',
                dataType:'json',
                data:data,
                success:function(data){
                    if (data.ERRORCODE == '0') {
                        showPage();
                        setTimeout(function(){
                            window.location.href="../Register/login";
                        }, 1000) //用于延时页面跳转
                   }else{
                        pswerror.show();
                   };
                },
                error:function(){
                    ajerror.show();
                }
            })
        }
    });
    
    

})










