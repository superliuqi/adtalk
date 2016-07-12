var base_URL = '/adtalk';                   //全局变量污染
var confirmInfo = false,$doc=$(document);

//加载页面，触发一次ajax，自动加载情景模式
getScene();


//选择省、市开始
var jqueryDom = {
        $doc: $(document),
        $city: $('#city'),
        $modal: $('#myModal'),
        $modalBody: $('#modalBody'),
        $main: $('.micro-main'),
        $channelList: $('#microList'),
        $province: $('#province'),
        $title: $('.check-title'),
        $remark: $('#checkRemark')
    },
    chinaDistrict, cityList, page = 1;

function getCityList() {
    $.getJSON(base_URL + '/public/js/chinaDistrict.json', function(data) {
        var body = {
            data: data
        }; //获取省区
        chinaDistrict = data;
        var option = '{@each data as it}<option value="${it.code}">${it.name}</option>{@/each}';
        jqueryDom.$province.append(juicer(option, body));
        jqueryDom.$province.change();
    });
}
jqueryDom.$doc.on('change', '#province', function() {
    var cityCode = $(this).val(),
        i = 0,
        len = chinaDistrict.length;
    if (cityCode == "") {
        jqueryDom.$city.html('<option value="">所属城市</option>');
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
    jqueryDom.$city.html(juicer(option, body));

});
//选择省、市结束

//表单验证
$(function() {      //change 应使用$(window).load()
    var clientH = null;   
    var h=null;  
    window.onload = function zhezhao(){     //change 不应该这样调用
        clientH = document.documentElement.clientHeight;  
        if(isSafari=navigator.userAgent.indexOf("Safari")>0){  
            h = document.body;  
        }else{  
            h =document.documentElement;  
        }  
    }  
    function showBg(elem){
        var w = (document.documentElement.clientWidth/2)-($("#"+ elem).width()/2);  
        $("#"+ elem).css("top",(h.scrollTop+ (clientH/2)-($("#"+ elem).height()/2))+"px");  
        $("#"+ elem).css("left",w+"px");  
        $("#fullbg").css("display","block");  
        $("#"+ elem).css("display","block");  
    }  
     
    getCityList();

    $('.fileSelect').imgPreview();

    $('#advertiseForm').validate({
        submitHandler:function(form){
            if(!!confirmInfo){
                form.submit();
            }else{
                showBg('dialog');
            }
            // alert("submitted");
            // closeBg('demo');
        },
        rules: {
            header: {
                required: true,
            },
            city: {
                required: true,
            },
            voices: {
                required: true,
            },
            textarea: {
                required: true,
            }
        }, 
        messages: {
            header: {
                required: '请填写广告标题'
            },
            city: {
                required: '请选择省份城市'
            },
            voices: {
                required: '请上传广告语音'
            },
            textarea: {
                required: '请输入广告语音内容'
            }
        },
        errorPlacement: function(error, element) {
            if (element.is(':radio'))
                error.appendTo(element.parent().next().next());
            else if (element.is('#phoneNumber')) {
                error.insertAfter(element.next());
            } else if (element.is('#city')) {
                error.insertAfter(element.parent().prev().children('select#province'));
            } else
                error.insertAfter(element);
        }
    });
});
//遮罩层点击确认提交form
$('#submit').on('click', function(){
    confirmInfo = true;
    $('form').submit();
});
//遮罩层点击取消，遮罩层隐藏
$('#dialog a').on('click', function(){
    $("#fullbg").hide();   
    $("#dialog").hide();
});


//多选框
$('input[type=checkbox]').labelauty();
//限制上传格式和大小
var isIE = /msie/i.test(navigator.userAgent) && !window.opera; 
function fileChange(target,id) { 
    var fileSize = 0; 
    var filetypes =[".mp3"]; 
    var filepath = target.value; 
    var filemaxsize = 1024*8;//8M 
    if(filepath){ 
        var isnext = false; 
        var fileend = filepath.substring(filepath.indexOf(".")); 
        if(filetypes && filetypes.length>0){ 
            for(var i =0; i<filetypes.length;i++){ 
                if(filetypes[i]==fileend){ 
                isnext = true; 
                break; 
                } 
            } 
        } 
        if(!isnext){ 
        alert("不接受此文件类型！"); 
        target.value =""; 
        return false; 
        } 
    }else{ 
        return false; 
    } 
    if (isIE && !target.files) { 
        var filePath = target.value; 
        var fileSystem = new ActiveXObject("Scripting.FileSystemObject"); 
        if(!fileSystem.FileExists(filePath)){ 
            alert("附件不存在，请重新输入！"); 
            return false; 
        } 
        var file = fileSystem.GetFile (filePath); 
        fileSize = file.Size; 
    } else { 
        fileSize = target.files[0].size; 
    } 

    var size = fileSize / 1024; 
    if(size>filemaxsize){ 
        alert("附件大小不能大于"+filemaxsize/1024+"M！"); 
        target.value =""; 
        return false; 
    } 
    if(size<=0){ 
        alert("附件大小不能为0M！"); 
        target.value =""; 
        return false; 
    } 
} 
//上传音频并播放
$(function () {
    $("#upload").change(function () {
        var objUrl = getObjectURL(this.files[0]);
        $("#audio").attr("src", objUrl);
        $("#audio")[0].play();
        $("#audio").show();
        getTime();
    });
});
/* 获取mp3文件的时间 兼容浏览器 */
function getTime() {
    setTimeout(function () {
        var duration = $("#audio")[0].duration;
        if(isNaN(duration)){
            getTime();
        }
        else{
            console.info("该歌曲的总时间为："+$("#audio")[0].duration+"秒")
        }
    }, 10);
}
// <!--把文件转换成可读URL-->
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

//侧边栏hover效果
$('.nav li').hover(function() {
    $(this).addClass('current');
}, function() {
   $(this).removeClass('current');
});

//选择时间或者情景模式触发一次 GetCount
$doc.on('click','input',function(){
    GetCount();
});
//选中复选框，相应价格+，取消价格—
function GetCount() {
    var conts = 0;
    $('input[type=checkbox]:checked').each(function(){
        conts = parseFloat(conts) + parseFloat($(this).data('price'));
    })
    $("#max").html((conts).toFixed(2));
    $("#min").html((conts).toFixed(2));
}
//情景模式
function getScene(){
    $.ajax({
        url: '/adtalk/index.php/Scene/sceneinfos',
        type: 'post',
        dataType: 'json',
        // async:false,
        //成功后获取sceneName、scenePrice追加到#scene
        success:function(data){
            var i =0,len = data.length,a='';
            for(i;i<len;i++){
                a +='<li><input type="checkbox" name="scene[]" class="labelauty" value='+data[i]['sceneName']+' data-labelauty='+data[i]['sceneName']+' data-price='+data[i]['scenePrice']+' id="labelauty-'+i+'" style="display: none;">';
                a +='<label for="labelauty-'+i+'"><span class="labelauty-unchecked-image"></span><span class="labelauty-unchecked">'+data[i]['sceneName']+'</span><span class="labelauty-checked-image"></span><span class="labelauty-checked">'+data[i]['sceneName']+'</span></label></li>';
            }
            $('#scene').append(a);
        }
    })
}









