/* 
* @Author: zhouyan
* @Date:   2015-12-30 20:16:04
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-22 19:20:48
*/
var $doc = $(document),$table =  $('#userInfoTB'),tableObj;
    var chinaDistrict ,companyType;

$(function	()	{
    getCityList();
    tableObj = $table.DataTable({
        oLanguage : { // 汉化
            sProcessing: "正在加载数据...",
            sLengthMenu: "显示_MENU_条 ",
            sZeroRecords : "没有您要搜索的内容",
            sInfo : "总记录数为 _TOTAL_ 条",
            sInfoEmpty : "记录数为0",
            sInfoFiltered : "(全部记录数 _MAX_  条)",
            sInfoPostFix : "",
            sSearch : "搜索",
            sUrl : "",
            oPaginate : {
                sFirst : "第一页",
                sPrevious : " 上一页 ",
                sNext : " 下一页 ",
                sLast : " 最后一页 "
            }
        },
        searching:false,
        bServerSide: true,
        sAjaxSource: "./getAllAccountInfo",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
			aoData.push(
				{"name": "name", "value": $('#name').val()},
				{"name": "telephone", "value": $('#telephone').val()},
				{"name": "startTime", "value": $('#startTime').val()},
				{"name": "endTime", "value": $('#endTime').val()},
				{"name": "companyName", "value": $('#companyName').val()},
				{"name": "accountStatus", "value": $('#accountStatus').val()},
				{"name": "checkStatus", "value": $('#checkStatus').val()},
                {"name": "cityCode", "value": $('.prov').val()}
			)
		}, 
		createdRow: function ( row, data, index ) {
			var accountStatus = data.accountStatus=="5"?"激活":"停用";
            var operate = '';
             $('td',row).eq(0).text(index+1);
			$('td',row).eq(10).text(accountStatus);
           	$('td',row).eq(7).text(checkUserStatus(data.checkStatus));
            if(data.checkStatus=="2" || data.checkStatus=="0"){
                operate+='<a class="btn btn-info btn-sm" href="accountDetail?userID='+data.userID+'&type=view">查看详情</a>';
            }
            if(data.checkStatus=="1"){
                operate+='<a class="btn btn-info btn-sm" href="accountDetail?userID='+data.userID+'&type=view">查看详情</a><a class="btn btn-warning btn-sm" href="accountDetail?userID='+data.userID+'&type=check">处理申请</a>';
            }
            if(data.checkStatus=="3"){
                operate+='<a class="btn btn-info btn-sm" href="accountDetail?userID='+data.userID+'&type=view">查看详情</a>';
            }
            if (data.accountStatus=="4") {
                operate+='<button class="btn btn-primary btn-sm active">激活</button>';
            };
            if (data.accountStatus=="5") {
                operate+='<button class="btn btn-danger btn-sm active">停用</button>';
            };
            $('td',row).eq(11).html(operate);
            $('td',row).eq(3).text(parseCityCode(data.cityCode));
            $('td',row).eq(4).text(companyType[data.companyType]);
            $('td',row).eq(9).text(setTimes(data.createTime));
        },
        columns: [
            { data: "id" },
            { data: "email" },
            { data: "companyName" },
            { data: "cityCode"},
            { data: "companyType" },
            { data: "name" },
            { data: "telephone" },
            { data: "checkStatus" },
            { data: "balance" },
            { data: "createTime" },
            { data: "accountStatus" },
            {
                defaultContent: ''
            }
        ]
    });
	laydate({
    	elem: '#startTime',
    	format: 'YYYY-MM-DD hh:mm'
	});
	laydate({
    	elem: '#endTime',
    	format: 'YYYY-MM-DD hh:mm'
	});
    
});
//模态框--确定按钮
$doc.on('click','#beSure',function(data){
    var accountStatus = $('#status').val()=="5"?"4":"5";
    var userID =  $('#userID').val();
    $.ajax({
        url:'disableAccount',
        type:'post',
        dataType:'json',
        data:{userID:userID,accountStatus:accountStatus}
    }).done(function(data){
        if (data.ERRORCODE=='0') {
            $('#myModal').modal('hide');
            tableObj.ajax.reload();
            
        };
    })
})

$doc.on('click','.active',function(){
    $('#myModal').modal('toggle');
    var data = tableObj.row( $(this).parents('tr') ).data();
    $('#userID').val(data.userID);
    $('#status').val(data.accountStatus);
    console.log(data.accountStatus);
    if ($('#status').val()=='5') {
        $('.modal-body p').html('停用之后，该账户将不能登录，确定停用？')
    };
    if ($('#status').val()=='4') {
        $('.modal-body p').html('激活之后，该账户将恢复正常使用，确定激活？')
    };
});

$doc.on('click','#search',function(){
	var sTime = Date.parse(new Date($('#startTime').val())) / 1000,
	eTime = Date.parse(new Date($('#endTime').val())) / 1000
	if(eTime<sTime){
		alert("TIME ERROR");
	}else{
		tableObj.ajax.reload();
	}
});

function retrieveData(sSource, aoData, fnCallback){
    $.ajax({
        type: "get",
        url: sSource,
        dataType: "json",
        data: {setData: JSON.stringify(aoData)}
    }).done(function (resp) {
        fnCallback(resp);
    });
};

//根据城市代码获取城市名字
function parseCityCode(cityCode){
    var city='';
    if(cityCode=="1"){
        return "全国";
    }
    $.ajax({
        url: '../Advertise/getCityName',
        type: 'POST',
        async:false,
        dataType: 'json',
        data:{cityCode:cityCode}
    }).done(function(data){
            if(data.ERRORCODE=="0" && data.RESULT.length>0){
                var result = data.RESULT,len = result.length,i=0;
                for (; i < len; i++) {
                   city+=result[i].cityName;
                };
            }else{
                city = '无数据';
            }
        }).fail(function() {
            city ='无数据';
        });
    return city;
};
 //选择省、市开始
function getCityList() {
    var $province= $('.prov');

    $.getJSON('../../../public/js/chinaDistrict.json', function(data) {
        var body = {
            data: data
        }; //获取省区
        chinaDistrict = data;
        var option = '{@each data as it}<option value="${it.code}">${it.name}</option>{@/each}';
        $province.append(juicer(option, body));
        $province.change();
    });
}
$doc.on('change', '.prov', function() {
        var cityCode = $(this).val(),
            $city = $('.city'),
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
        $city.html(juicer(option, body));
    });
    //选择省、市结束