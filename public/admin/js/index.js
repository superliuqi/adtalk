var $doc = $(document);

//用户、广告、金额数据
function data(btntype){
  $.ajax({
    //url: 'admin/Bench/getAllBenchInfo',
    url: './getAllBenchInfo',
    type: 'POST',
    dataType: 'json',
    data:{type:btntype}
  }).done(function(data){
    console.log(data);
    if(data.ERRORCODE == '0'){ 
      var data = data.RESULT;
      $('#userCount').html(data.userCount);
      $('#adCount').html(data.adCount);
      $('#adPlayCount').html(data.adPlayCount);
      $('#money').html(data.money);
    }
  }).fail(function() {
    console.log('无数据');
  });
}

//点击时间按钮
//显示数据
$doc.on('click','.all',function(){
  $('.all').addClass('indexActive');
  $(".btn-data button:not('.all')").removeClass('indexActive');
  data(1);
})

$doc.on('click','.today',function(){
  $('.today').addClass('indexActive');
  $(".btn-data button:not('.today')").removeClass('indexActive');
  data(2);
})

$doc.on('click','.yesterday',function(){
  $('.yesterday').addClass('indexActive');
  $(".btn-data button:not('.yesterday')").removeClass('indexActive');
  data(3);
})

$doc.on('click','.last-7',function(){
  $('.last-7').addClass('indexActive');
  $(".btn-data button:not('.last-7')").removeClass('indexActive');
  data(4);
})

$doc.on('click','.last-30',function(){
  $('.last-30').addClass('indexActive');
  $(".btn-data button:not('.last-30')").removeClass('indexActive');
  data(5);
})

$(function(){
  //数据显示
  $('.all').addClass('indexActive');
  data(1);

  //地区
  $.ajax({
        //url: 'admin/Bench/getCityRanking',
        url: './getCityRanking',
        type: 'POST',
        dataType: "json"
    }).done(function (data) {
      console.log(data);
      if(data.ERRORCODE=="0"){
        showCity(data.RESULT.city);
        showMoney(data.RESULT.money);
      }else{
        alert(data.RESULT);
      }
    }).fail(function() {
    console.log('无数据');
  });
})

juicer.register('number',round);
function showCity(data){
  var data = {data:data};
  var detailCity = '{@each data as it}\
  <a href="#" class="list-group-item"><span class="badge">${it.rank}</span>${it.city}</a>\
  {@/each}';
  $('#city').html(juicer(detailCity,data));
};
function showMoney(data){
  var data = {data:data};
  var detailMoney = '{@each data as it}\
  <a href="#" class="list-group-item"><span class="badge">${it.money|number}</span>${it.city}</a>\
  {@/each}';
  $('#moneyCity').html(juicer(detailMoney,data));
};

function round(num){
  num = Math.round(num*100)/100;
  return num;
}
