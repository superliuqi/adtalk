/* 
* @Author: zhouyan
* @Date:   2015-10-23 14:02:51
* @Last Modified by:   zhouyan
* @Last Modified time: 2015-12-10 21:05:57
*/



$(function (){

    //声明全局变量
    var $doc = $(document),
        $email = $('#email'),
        $code = $('#inputCode'),
        $emailerror = $('.emaile'),
        $codeerror = $('.codee');

    var checkStatus=1;

    //获取验证码
    changeCode();

    //点击按钮换一张验证码
    $doc.on('click', '#changeCode', function(event) {
       changeCode();
    });

    //邮箱输入框失去焦点，验证邮箱是否注册
    $doc.on('blur', '#email',function (){
        var emailval = $email.val();
        if(!/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(emailval)){
            $('.email-error').show();
            return false;
        }
        // validatecode($code.val());
        validateemail(emailval);
    });

    //验证码输入框失去焦点，验证验证码是否正确
    $doc.on('blur', '#inputCode',function (){
        var codeval = $code.val();
        if(codeval.length < 4){
            $('#registersBtn').attr('disabled',true).addClass('disabled');
            return false;
        }
        validatecode(codeval);
        // validateemail($email.val());
    });

    //验证码输入框失去焦点，验证验证码是否正确
    $doc.on('focus', '#inputCode',function (){
        $('.codes').hide();
        $codeerror.hide();
    });

    $doc.on('focus', '#email', function(event) {
        $('.ajax-error').hide();
        $('.email-error').hide();
    });

$doc.on('click','#registersBtn',function (event){
    if(checkStatus!=0){
        return false;
    }
    $.ajax({
        url: 'forgetPwdSendEmail',
        type: 'post',
        dataType:'json',
        data:{email: $email.val()},
        success:function(data){
            if (data.ERRORCODE == '0') {
                window.location.href="verification";   
            } else{
                $('.ajax-error').show();
            };
        },
        error:function(){

        }
    })
});
//验证邮箱
function validateemail(emailval){
    $.ajax({
        url: 'validateLoginEmail',
        type: 'POST',
        dataType: 'json',
        data:{email:emailval},
        success:function(data){
            if (data.ERRORCODE == '0') {
                checkStatus=0;
                $('.emails').show();
                $('#registersBtn').attr('disabled',false).removeClass('disabled');
            } else{
                checkStatus=1;
                $emailerror.show();
                $('#registersBtn').attr('disabled',true).addClass('disabled');
            };
        },
        error:function(){
            $emailerror.show();
        }
    })
}

//验证验证码
function validatecode(codeval){
    $.ajax({
        url: 'validatecode',
        type: 'POST',
        dataType: 'json',
        data:{code:codeval},
        success:function(data){
            if (data.ERRORCODE == '0') {
                checkStatus=0;
                $('.codes').show();
                $('#registersBtn').attr('disabled',false).removeClass('disabled');
            } else{
                checkStatus=1;
                $codeerror.show(); 
                $('#registersBtn').attr('disabled',true).addClass('disabled'); 
                return false;
            };
        },
        error:function(){
            console.log(111);
        }
    })
}
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

      }
    });   
}

});