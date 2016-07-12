$(function() {

  //声明变量
  $doc=$(document);

  //点击登录
  $doc.on('click', '#loginBtn', function(event) {
    var nameValue = $('#name').val(),
        passwordValue = $('#password').val();
    if (nameValue==''||passwordValue=='') {
      $(".error").slideDown(2000);
      event.preventDefault();
      return;
    };
    toLogin();
  });
  function toLogin(){
    
  };
  function showError(){
    $(".error").slideDown(2000);
  }
  function hideError(){
    $(".error").slideUp(2000);
  }
});