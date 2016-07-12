/* 
* @Author: zhouyan
* @Date:   2015-12-30 20:16:04
* @Last Modified by:   zhouyan
* @Last Modified time: 2016-01-22 19:21:28
*/
var $doc = $(document),$table =  $('#userInfoTB'),tableObj;
    var chinaDistrict ,companyType;

$(function  ()  {
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
        sAjaxSource: "./getCompanyStatistics",
        fnServerData: retrieveData,
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        fnServerParams:function (aoData) {
              aoData.push(
                {"name": "cityCode", "value": $('.prov').val()},
                {"name": "companyName", "value": $('#companyName').val()}
              )
        }, 
        createdRow: function ( row, data, index ) {
            var totalPrice = data.namingPrice+data.trailerPrice;
            $('td',row).eq(0).text(index+1);
            $('td',row).eq(7).text(data.naming+data.trailer);
            if(data.trailerPrice==0){
                $('td',row).eq(8).text(new Number(data.namingPrice).toFixed(2));
            }else if(data.namingPrice==0) {
                $('td',row).eq(8).text(new Number(data.trailerPrice).toFixed(2));
            }else{
                $('td',row).eq(8).text(new Number(data.namingPrice+data.trailerPrice).toFixed(2));

            }
            
            //$('td',row).eq(8).text(totalPrice.toFixed(2));
            $('td',row).eq(2).text(parseCityCode(data.cityCode))
        },
        columns: [
            { data: "id" },
            { data: "companyName" },
            { data: "cityCode" },
            { data:  "naming"},
            { data: "namingPrice" },
            { data:  "trailer"},
            { data: "trailerPrice" },
            { defaultContent: '' },            
            { defaultContent: '' }
        ]
    });
    
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
                city = '';
            }
        }).fail(function() {
            city ='';
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