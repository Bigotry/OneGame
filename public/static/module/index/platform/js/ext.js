  $(function () {

    //搜索功能
    $("#search").click(function(){
        
        window.location.href = searchFormUrl(this);
    });

    //回车搜索
    $(".search-input").keyup(function(e){
        if(e.keyCode === 13){
                $("#search").click();
                return false;
        }
    });
    
    //ajax get请求
    $('.ajax-get').click(function(){
        
        var target;
        
        if ( $(this).hasClass('confirm') ) {
            
            if(!confirm('确认要执行该操作吗?')){
                
                return false;
            }
        }
        
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            
            if ($(this).attr('is-jump') == 'true') {
                
                location.href = target;
                
            } else {
                $.get(target).success(function(data){
                    
                    obalert(data);
                });
            }
        }
        
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function(){

        form = $('.target-form');
        
        $.post(form.get(0).action, form.serialize()).success(function(data){

            obalert(data);
        });
            
        return false;
    });
    
});

/**
 * 提示或提示并跳转
 */
var obalert = function (data) {
        
    if (data.code) {
        
        toast.success(data.msg);
    } else {
        
        if(typeof data.msg == "string"){
            
             toast.error(data.msg);
        }else{
            
            var err_msg = '';
            
            for(var item in data.msg){ err_msg += "Θ " + data.msg[item] + "<br/>"; }
            
            toast.error(err_msg);
        }
    }
        
    if(data.url){

        setTimeout(function(){

            location.href = data.url;
        },1500);
    }
    
    if(data.code && !data.url){

        setTimeout(function(){

            location.reload();
        },1500);
    }
};

/**
 * 操纵toastor的便捷类
 * @type {{success: success, error: error, info: info, warning: warning}}
 */
var toast = {
    /**
     * 成功提示
     * @param text 内容
     * @param title 标题
     */
    success: function (text, title) {
    	
    	$(".toast").remove();
    	
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.success(text, title);
    },
    /**
     * 失败提示
     * @param text 内容
     * @param title 标题
     */
    error: function (text, title) {
    	
    	$(".toast").remove();
    	
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.error(text, title);
    },
    /**
     * 信息提示
     * @param text 内容
     * @param title 标题
     */
    info: function (text, title) {
    	
    	$(".toast").remove();
    	
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.info(text, title);
    },
    /**
     * 警告提示
     * @param text 内容
     * @param title 标题
     */
    warning: function (text, title) {

    	$(".toast").remove();
    	
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr.warning(text, title);
    }
};

/**
 * 搜索表单url
 */
var searchFormUrl = function (obj) {

    var url = $(obj).attr('url');
    var query  = $('.search-form').find('input').serialize();
    query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
    query = query.replace(/^&/g,'');
    if( url.indexOf('?')>0 ){
        url += '&' + query;
    }else{
        url += '?' + query;
    }
    
    return url;
};
