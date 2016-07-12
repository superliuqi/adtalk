$(function() {
    //表单验证
    getCityList();

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
        $.get("../../public/js/chinaDistrict.json", function(data) {
            var body = {
                data: data
            }; //获取省区
            var option = '{@each data as it}<option value="${it.code}">${it.name}</option>{@/each}';
            jqueryDom.$province.append(juicer(option, body));
            chinaDistrict = data;
        });
    }
    jqueryDom.$doc.on('change', '#province', function() {
        var cityCode = $(this).val(),
            i = 0,
            len = chinaDistrict.length;
        if (cityCode == "") {
            jqueryDom.$city.html('<option value="">所属城市</option>').change();
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
        jqueryDom.$city.html(juicer(option, body)).change();
    });
    //选择省、市结束

    var codeError = $('<label class="error" id="inputCode-error" for="inputCode">验证码错误</label>');   //change 只使用了一次 不应该放在这里
    $('.fileSelect').imgPreview();

    //验证身份证号码
    $.validator.addMethod('isIDCard', function(value, element) {
        var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        return reg.test(value);

    }, '身份证格式不正确');

    //验证手机号码
    $.validator.addMethod('isTelephone', function(value, element) {
        var reg = /^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/;
        return reg.test(value);

    }, '手机格式不正确');
    //表单必填和长度验证
    $('#messageForm').validate({
        rules: {
            companyName: {
                required: true,
                minlength: 6
            },
            licenseNumber: {
                required: true,
                minlength: 6
            },
            orgCode: {
                required: true,
                minlength: 8
            },
            registrationNumber: {
                required: true,
                minlength: 5
            },
            companyType: {
                required: true
            },
            city: {
                required: true
            },
            companyRegAdd: {
                required: true
            },
            companyPhone: {
                required: true,
                minlength: 8
            },
            name: {
                required: true,
                minlength: 2
            },
            IDNumber: {
                required: true,
                minlength: 18,
                isIDCard: true
            },
            telephone: {
                required: true,
                minlength: 11,
                isTelephone:true
            },
            code: {
                required: true,
                minlength: 4,
            },
            prove: {
                required: true
            },
            trcPic: {
                required: true
            },
            cocPic: {
                required: true
            },
            brPic: {
                required: true
            }
        },
        messages: {
            companyName: {
                required: '请输入公司全称',
                minlength: '请输入公司全称'
            },
            licenseNumber: {
                required: '请输入公司营业执照注册号',
                minlength: '请输入公司营业执照注册号'
            },
            orgCode: {
                required: '请输入公司组织机构代码证号',
                minlength: '请输入公司组织机构代码证号'
            },
            registrationNumber: {
                required: '请输入公司税务登记证号',
                minlength: '请输入公司税务登记证号'
            },
            companyType: {
                required: '请选择行业类别'
            },
            city: {
                required: '请选择省份城市'
            },
            companyRegAdd: {
                required: '请输入公司详细地址'
            },
            companyPhone: {
                required: '请输入公司电话号码',
                minlength: '请输入正确的电话号码'
            },
            name: {
                required: '请输入运营者姓名',
                minlength: '请输入正确的运营者姓名'
            },
            IDNumber: {
                required: '请输入运营者身份证号码',
                minlength: '请输入正确的运营者身份证号码'
            },
            telephone: {
                required: '请输入运营者手机号码',
                minlength: '请输入正确的运营者手机号码',
                isTelephone:'手机格式不正确'
            },
            code: {
                required: '请输入验证码',
                minlength: '验证码输入错误'
            },
            prove: {
                required: '请上传授权证明'
            },
            trcPic: {
                required: '请上传税务登记证'
            },
            cocPic: {
                required: '请上传组织结构代码证'
            },
            brPic: {
                required: '请上传企业工商营业执照'
            }
        },
        //错误提示显示位置
        errorPlacement: function(error, element) {
            if (element.is(':radio'))
                error.appendTo(element.parent().next().next());
            else if (element.is('#phoneNumber')) {
                error.insertAfter(element.next());
            } else if (element.is('#city')) {
                error.insertAfter(element.parent().prev().children('select#province'));
            } else
                error.insertAfter(element);
        },
    });
    
    //手机获取验证码
    var wait = 10;
    var timer;
    //获取验证按钮倒计时
    function time(o) {
        if (wait == 0) {
            clearTimeout(timer);
            o.removeAttribute("disabled");
            o.value = "获取验证码";
            wait = 10;
            $('.codeError').hide();
        } else {
            o.setAttribute("disabled", true);   //change 使用了jquery为何还要使用setAttribute
            o.value = "重新发送(" + wait + ")";
            timer = setTimeout(function() {
                    wait--;
                    time(o)
                },
                1000)
        };
    }
    //单击按钮获取验证码
    $('#phoneCodebtn').on('click', function(event) {
        var getphone = $('#phoneNumber').val();
        console.log(getphone);
        //判断是否是手机格式然后发送ajax请求
        if(!/^[(86)|0]?(13\d{9})|(14\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/.test(getphone)){
            // $('#phoneCodebtn').focus(); //change 去除
            return false;
        }
        if (getphone.length === 11) {    //change 可只判断!==11的情况 美化代码 应使用===严格相等或!==严格不等来判断
            clearTimeout(timer);
            time(this);
            $.ajax({
                url: 'getphonecode',
                type: 'post',
                dataType: 'json',
                data: {
                    phone: getphone
                },
                success: function(data){
                    if (data.ERRORCODE == '0') {
                        //coding
                    } else{
                        $('.codeError').show();
                    };
                }
            })

        } else {
            $('#phoneNumber').focus();  //change 去除
        };

    });
    // $('#phoneNumber').on('focus',function(event) {
    //     $('.codeError').css('display', 'none');
    //     $('.codeSuccess').css('display', 'none');
    // });

    //验证验证码
    $('#code').on('blur', function() {
        var codeValue = $('#code').val();
        if (codeValue.length == 4) {    //change 可只判断!==4的情况 美化代码 应使用===严格相等或!==严格不等来判断
            $.ajax({
                url: 'validatephonecode',
                type: 'post',
                dataType: 'json',
                data: {
                    phonecode: codeValue,
                    phone:$('#phoneNumber').val()
                },
                success:function(data){
                    if (data.ERRORCODE == '0') {
                       $('.ok').css('display', 'inline-block');
                       $('#submitBtn').attr('disabled',false).removeClass('disabled'); 
                    } else{
                        //change ERRORCODE存在多种可能性
                        codeError.insertAfter ('#code');
                        $('#inputCode-error').css('display', 'inline-block');
                        $('#registersBtn').attr('disabled',false).removeClass('disabled');
                    };
                },
                error:function(){
                    alert('请稍后再试');
                }
            });      
            
        } else{
            //coding
            // codeError.insertAfter ('#code');
        };
        
    });
    $('#code').on('focus',function(event) {
       $(this).siblings('span,#inputCode-error').hide();    //change 是否有问题
       return false;
    });
});
//表单验证