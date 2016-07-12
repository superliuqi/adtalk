/* 
* @Author: zhouyan
* @Date:   2015-09-25 16:55:12
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-15 13:47:54
*/


 var companyType;
 //行业类别 
(function getTradeList(){
    $.getJSON('../../../public/js/trade.json', function(data) {
        companyType=data;
    });
}());
//广告状态判断
function checkStatus(adStatus){
    switch(parseInt(adStatus)){
        case 0:
            return '待审核';
            break;
        case 1:
            return '投放中';
            break;
        case 2:
            return '待调整';
            break;
        case 3:
            return '暂停投放';
            break;
        case 4:
            return '草稿';
            break;
        default:
            return '无';
            break;
    }
}
//广告形式判断
function checkadShape(adShape){
    switch(parseInt(adShape)){
        case 1:
            return '冠名广告';
            break;
        case 2:
            return '尾标广告';
            break;
        case 4:
            return '开机广告';
            break;
        case 5:
            return '路况看板广告';
            break;
        default:
            return '无';
            break;
    }
}
//广告位
function parseAdShape(adShape){
    switch(parseInt(adShape)){
        case 1:
            return '路况声音广告';
            break;
        case 2:
            return '电子LOGO广告';
            break;
        case 3:
            return '思聪LOGO广告';
            break;
        case 4:
            return '角标LOGO广告';
            break;
        default:
            return '无';
            break;
    }
}
//时间戳转化为标准时间
function setTimes(time){
    var dateTime=new Date(time * 1000);
    var year=dateTime.getFullYear();
    var month=dateTime.getMonth() + 1;
    var day=dateTime.getDate();
    var hour=dateTime.getHours();
    var minute=dateTime.getMinutes();
    var second=dateTime.getSeconds();
    return year+'-'+add0(month)+'-'+add0(day)+' '+add0(hour)+':'+add0(minute)+':'+add0(second);
}
function add0(num){
    var num = num<10?'0'+num:num;
    return num;
}

//根据状态不同，button不同
function checkbtn($adStatus){
    var $adStatus = parseInt($adStatus);
    switch($adStatus){
        case 0:
            return '<button class="stop">停用</button>';
            break;
        case 1:
            return '<button class="stop">停用</button>';
            break;
        case 2:
            return '<button class="stop">停用</button><button class="edit">编辑</button>';
            break;
        case 3:
            return '';
            break;
        case 4:
            return '<button class="stop">停用</button><button class="edit">编辑</button><button class="submit">提交审核</button>';
            break;
        default:
            return '无';
            break;
    }
}

//正常时间转换时间戳
function getTimestamp(param){
	if ( param == '') {
		return param = '';
	}
	return (new Date(param.replace(/\-/g,"/"))-0)/1000;
}
//根据城市代码获取城市名字
function parseCityCode(cityCode){
    var city='';
    if(cityCode=="1"){
        return "全国";
    }
    $.ajax({
        url: './getCityName',
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
            }
        }).fail(function() {
            city ='无数据';
        });
    return city;
};
//充值状态判断
function checkRechargeStatus(adStatus){
    switch(parseInt(adStatus)){
        case 0:
            return '待线下打款';
            break;
        case 1:
            return '待审核';
            break;
        case 2:
            return '充值成功';
            break;
        case 3:
            return '充值失败';
            break;
        case 4:
            return '取消充值';
            break;
        default:
            return '无';
            break;
    }
}
//账户状态
function checkUserStatus($Status){
    var $Status = parseInt($Status);
    switch ($Status){
        case 0:
            return '未完善';
            break;
        case 1:
            return '待审核';
            break;
        case 2:
            return '审核通过';
            break;
        case 3:
            return '审核失败';
            break;
        case 4:
            return '停用';
            break;
        case 5:
            return '激活';
            break;
        default:
            return '无';
            break;
    }
}
$(document).on('change','.fileSelect',function (){
    var reader = new FileReader(),$this=$(this);
    reader.readAsDataURL(this.files[0]);
    var size = this.files[0].size;
    reader.onload = function(e){
        var data = {
            size:size,
            image:e.target.result
        };
        $.ajax({
            url: '../Register/uploadImage',
            type: 'POST',
            async:false,
            dataType: 'json',
            data:data
        }).done(function(data){
            if(data.ERRORCODE=="0"){
                $this.next('input[type="hidden"]').val(data.RESULT.url);
                $this.nextAll('img').attr('src',data.RESULT.url).removeClass('hidden');
            }
        });
    }
});