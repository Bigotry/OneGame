//登录
$(".btn-tologin").click(function(){

    $('.fakeloader').show();

    var self = $('.login-form');

    $.post(login_action_url, self.serialize(), success, "json");
    return false;

    function success(data){

        setTimeout(function(){ $('.fakeloader').hide(); }, 500);

        obalert(data);
    }
});

//回车
$("input").keyup(function(e){
    if(e.keyCode === 13){
        $(".btn-tologin").click();
        return false;
    }
});

function toLogin()
{
   location.href="/loginapi/QQAPI/oauth/index.php";
}
