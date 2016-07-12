/* 
* @Author: zhouyan
* @Date:   2015-10-27 16:24:06
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-11 13:21:12
*/
$(function(){

	//声明全局变量
	var email = $('#email');

	//ajax获取信息
	$.ajax({
		url: 'companyData',
		type: 'POST',
		dataType: 'json',
		success:function(data){
			if (data.ERRORCODE = '0') {
				var emailval = data.RESULT['email'],
				    logintime = data.RESULT['logintime'],
				    _RESULT = data.RESULT;

				//获取到的email添加到页面
				email.html(emailval);
				//转换时间戳为正常日期添加到页面
				$('.loginTime span').html(setTimes(logintime));
				//广告总条数
				$('#advertisesum').html(_RESULT['adCount']);
				//广告待审核
				$('#advertisewait').html(_RESULT['adCheck']);
				//广告待调整
				$('#advertisefail').html(_RESULT['adModulation']);
				//广告播放总条数
				$('#adPlayCount').html(_RESULT['adPlayCount']);
				//账户余额
				$('#amountfinal').html(_RESULT['balance']);
				//待线下打款
				$('#linePlayMoney').html(_RESULT['linePlayMoney']);
				//待审核
				$('#checkMoney').html(_RESULT['checkMoney']);
				//充值失败
				$('#payFail').html(_RESULT['payFail']);
				_RESULT.checkStatus!="0"?$('.notice').hide():'';

			} else{
				alert('ERRORCODE不是0');
			};
		},
		error:function(){
			alert('请稍后再试');
		}
	});
});