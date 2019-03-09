//搜索功能
$("#search").click(function(){
    var url = $(this).attr('url');
    var query  = $('.search-form').find('input').serialize();
    query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
    query = query.replace(/^&/g,'');
    if( url.indexOf('?')>0 ){
        url += '&' + query;
    }else{
        url += '?' + query;
    }
    window.location.href = url;
});

//回车搜索
$(".search-input").keyup(function(e){
    if(e.keyCode === 13){
        $("#search").click();
        return false;
    }
});