var rules = {
  register_rules:{
    rules: {
      email: {
        required: true,
        email: true
      },
      verifyCode: {
        required: true,
        number: true
      }
    },
    errorClass:'uk-text-danger',
    messages: {
       email: {
        required: "请输入Email地址",
        email: "请输入正确的email地址"
       },
       verifyCode: {
        required: "请输入验证码",
        number: "验证码应为数字"
       }
    }
  },
  userInfo_rules:{
    rules: {
      corporateName: {
        required: true,
        xss: true
      },
      companyName: {
        required: true,
        xss: true
      },
      companyRegTime: {
        required: true,
        dateISO:true
      },
      companyRegAdd: {
        required: true,
        xss: true
      },
      companyBank: {
        required: true,
        xss: true
      },
      bankAccount: {
        required: true,
        digits:true
      },
      licenseNumber: {
        required: true,
        minlength:15
      },
      licenseScanPreview: {
        required: true,
        extension:'jpg|jpe?g|png|gif'
      },
      name: {
        required: true,
        xss: true
      },
      telephone: {
        required: true,
        minlength:11,
        phone: true
      },
      sellerName: {
        required: true,
        xss: true
      },
      sellerAdd: {
        required: true,
        xss: true
      },
      verifyCode: {
        required: true,
        minlength: 4
      }
    },
    errorClass:'uk-text-danger',
    messages: {
      corporateName: {
        required: "请输入法人代表"
      },
      companyName: {
        required: "请输入公司名称"
      },
      companyRegTime: {
        required: "请选择公司注册时间",
        dateISO:"请选择正确的公司注册时间"
      },
      companyRegAdd: {
        required: "请输入公司注册地址"
      },
      companyBank: {
        required: "请输入公司开户银行"
      },
      bankAccount: {
        required: "请输入银行账户",
        digits: "请输入正确的银行账户"
      },
      licenseNumber: {
        required: "请输入营业执照注册号",
        minlength:"请输入15位营业执照注册号"
      },
      licenseScanPreview: {
        required: "请上传营业执照扫描件"
      },
      name: {
        required: "请输入运营者姓名"
      },
      telephone: {
        required: "请输入手机号码",
        minlength: "请输入正确的手机号码",
        phone: "请输入规范的手机号"
      },
      sellerName: {
        required: "请输入商店名称"
      },
      sellerAdd: {
        required: "请输入商家联系地址"
      },
      verifyCode: {
        required: "请输入短信验证码",
        minlength: "请输入四位短信验证码"    
      }     
    }
  },
  goods_rules:{
    rules: {
      goodsName:{
        required: true,
        xss: true
      },
      subtitle:{
        required: true,
        xss: true
      },
      keywords:{
        required: true,
        xss: true
      },
      goodsCount:{
        required: true,
        digits:true
      },
      shopPrice:{
        required: true,
        micoin:true
      },
      marketPrice:{
        required: true,
        price:true
      },
      startTime: {
        required: true,
        dateISO: true
      },
      endTime: {
        required: true,
        dateISO: true
      },
      goodsImg: {
        required: true,
        extension:'jpg|jpe?g|png|gif'
      },
      goodsThumb:{
        extension:'jpg|jpe?g|png|gif'
      },
      getAging:{
        required: true,
        digits:true
      },
      address:{
        required: true,
        xss: true
      },
      brief:{
        required: true,
        xss: true
      }
    },
    errorClass:'uk-text-danger',
    messages: {
      goodsName:{
        required: "请输入商品名称",
        xss: "请输入规范的商品名"
      },
      subtitle:{
        required: "请输入商品副标题",
        xss: "请输入规范的商品副标题"
      },
      keywords:{
        required: "请输入商品关键字",
        xss: "请输入规范的商品关键字"
      },
      goodsCount:{
        required: "请输入库存数量",
        digits: "请输入规范的库存数量"
      },
      shopPrice:{
        required: "请输入当前售价",
        micoin: "请输入规范的密点数"
      },
      marketPrice:{
        required: "请输入市场售价",
        price: "请输入规范的售价"
      },
      startTime: {
        required: "请选择商品开始销售时间",
        dateISO:"请选择正确时间格式"
      },
      endTime: {
        required: "请选择商品开始销售时间",
        dateISO:"请选择正确时间格式"
      },
      goodsImg: {
        required: "请上传商品图片",
        extension:"请注意文件格式"
      },
      goodsThumb:{
        extension:"请注意文件格式"
      },
      getAging:{
        required: "请输入过期时间",
        digits:"请输入整数"
      },
      address:{
        required: "请输入具体地址",
        xss: "请输入规范文字"
      },
      brief:{
        required: "请输入商品的简要描述",
        xss: "请输入规范文字"
      }
    }
  },
  shop_rules:{
    rules:{
      storeName:{
        required: true,
        xss: true
      },
      address: {
        required: true,
        xss: true
      },
      manager:{
        required: true,
        xss: true
      },
      telephone:{
        required: true,
        minlength: 11,
        xss: true
      },
      startTime:{
        required: true,
        time: true
      },
      endTime:{
        required: true,
        time: true
      },
      username:{
        required: true,
        xss: true
      },
      password: {
        required: true,
        rangelength: [6,12],
        xss: true
      },
      agpassword: {
        required: true,
        rangelength: [6,12],
        equalTo: "#password"
      }
    },
    errorClass:'uk-text-danger',
    messages:{
      storeName:{
        required: "请输入店铺名称",
        xss: "请输入规范店铺名称"
      },
      address: {
        required: "请输入店铺的详细地址",
        xss: "请输入规范的店铺地址"
      },
      manager:{
        required: "请输入店长姓名",
        xss: "请输入规范的店长姓名"
      },
      telephone:{
        required: "请输入联系电话",
        minlength: "您输入的联系电话有误",
        xss: "请输入规范的联系电话"
      },
      startTime:{
        required: "请填写营业时间"
      },
      endTime:{
        required: "请填写营业时间"
      },
      username:{
        required: "请输入用户名",
        xss: "请输入规范的用户名"
      },
      password: {
        required: "请输入密码",
        rangelength: "密码长度必须在6到12位之间",
        xss:"不能包含特殊符号"
      },
      agpassword: {
        required: "请输入确认密码",
        rangelength: "确认密码必须在6到12位之间",
        equalTo: "两次输入密码不一致"
      }
    }
  },
  redeem_rules:{
    rules: {
      cardNumber:{
        required:true,
        minlength:10,
        digits:true
      },
      cardPassword:{
        required:true,
        minlength:5,
        digits:true
      }
    },
    errorClass:'uk-text-danger',
    messages: {
      cardNumber:{
        required:"请输入卡号",
        minlength:"请输入十位数字的卡号",
        digits:"请输入十位数字的卡号"
      },
      cardPassword:{
        required:"请输入卡密",
        minlength:"请输入五位数字的卡密",
        digits:"请输入五位数字的卡密"
      }
    }
  }
}
