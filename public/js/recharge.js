/* 
* @Author: zhouyan
* @Date:   2015-09-25 16:55:12
* @Last Modified by:   zhouyan
* @Last Modified time: 2015-12-31 18:37:26
*/

$(function() {

	//申明全局变量
	var $amountError = $('.amount-error'),
		$inAmount = $('.in-amount'),
		$number = $('#number'),
		$sendCode = $('.send-code'),
		$userID = $('#userID').val();


	//查询余额
	$.ajax({
		url: './userBalance',
		type: 'POST',
		dataType: 'json',
		data: {userID: $userID},
		success:function(data){
			if (data.ERRORCODE == '0') {
				$('#userBalance').html(data.RESULT.balance);
			} else{
				console.log(1111);
			};
		},
		error:function(){
			console.log(22222);
		}
	})

	//获取汇款识别码
	$(document).on('click', '#btn-code', function(event) {
		var $money = $('#amount').val();
		if ($money < 100) {
			$amountError.show();
			return false;
		};

		$.ajax({
			url: './getRemitUDID',
			type: 'POST',
			dataType: 'json',
			data: {
				money:$money,
				userID:$userID
			},
			success:function(data){
				if (data.ERRORCODE == '0') {
					//将输入的值传入充值金额
					$number.html($money);
					//流水号
					$('#runCode').html(data.RESULT.runCode);
					//汇款识别码
					$('.remitIdentCode').html(data.RESULT.remitIdentCode);
					//输入框页面隐藏
					$inAmount.hide();
					//汇款码页面显示
					$sendCode.show();
				} else{

				};
			},
			error:function(){

			}
		})

	});
});