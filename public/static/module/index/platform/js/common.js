
        //$(function(){
        $("#page_menu_index").addClass('on');

        var username = '';
        if (username){
            $('#header_username,#top_username').html(username);

            $('.header-login-body:not(.header-logined)').addClass('hide');
            $('.header-logined').removeClass('hide');

            $('.dt_after').removeClass('hide');
            $('.dt_before').addClass('hide');
        }
        
        //微信弹出
        $("#dt_weixin").hover(function(){
            $("#dt_weixin_con").show();
        },function(){
            $("#dt_weixin_con").hide();
        });

        //});
     

        $(function(){
            //消息
            $(".g_top_msg").hover(function(){
                $(".top_msg_li").show();
            },function(){
                $(".top_msg_li").hide();
            });

            //热门游戏
            $(".game_all").hover(function(){
                $(".game_all_list").show();
                $(".game_all_name ul li").hover(function(){
                    var _index = $(this).index();
                    $(".game_all_img ul li").eq(_index).show().siblings("li").hide();
                });
            },function(){
                $(".game_all_list").hide();
            });
        })
   

$(function(){
    popPosition();
    $(".ui-inputText").each(function(){//文本框统一事件
        var tobj=$(this);
        var tval=$(this).attr("defaultText");
        if(tval!=""&&tobj.val()==""){
            tobj.val(tval);
        }else{
            tobj.css('color','#777');
        }
        tobj.focus(function(){
            tobj.addClass("ui-inputText-focus");
            if(tval==tobj.val()){
                tobj.val("");
            }
        });
        tobj.blur(function(){
            tobj.removeClass("ui-inputText-focus");
            if(tval!=""&&tobj.val()==""){
                tobj.val(tval);
            }else{
                tobj.css('color','#777');
            }
        });
    });
});
function popOpen(){
    $(".ui-pop-mask").removeClass("hide");
    $(".ui-pop").removeClass("hide");
    $(".ie6-ui-pop-mask").removeClass("hide");
}
function popPosition(){
    var tobjMT=-($(".ui-pop").height()+8)/2;
    if($("#container").height()>$(window).height()){
        $(".ui-pop-mask").height($("#container").height());
        $(".ie6-ui-pop-mask").height($("#container").height());
    }else{
        $(".ui-pop-mask").height($(window).height());
        $(".ie6-ui-pop-mask").height($(window).height());
    }
    $(".ui-pop").css({"marginTop":tobjMT});
    $(".ui-pop-close").click(function(){
        $(".ui-pop-mask").addClass("hide");
        $(".ui-pop").addClass("hide");
        $(".ie6-ui-pop-mask").addClass("hide");
    })
}
function tabTurn(obj){
    obj.each(function(){
        var tobj=$(this);
        $(this).find("li").each(function(index){
            if(index==0){
                $(this).addClass("act");
            }
            if($(this).html()!=""){
                $(this).click(function(){
                    $(this).siblings().removeClass("act");
                    $(this).addClass("act");
                    //alert(tobj.parent().find(".tab-obj").attr("class"));
                    tobj.parent().find(".tab-obj").addClass("hide");
                    tobj.parent().find(".tab-obj:eq("+index+")").removeClass("hide");
                })
            }
        }).eq(0).click();
    })
}
window.onresize = function(){
    if($("#container").height()>$(window).height()){
        $(".ui-pop-mask").height($("#container").height());
        $(".ie6-ui-pop-mask").height($("#container").height());
    }else{
        $(".ui-pop-mask").height($(window).height());
        $(".ie6-ui-pop-mask").height($(window).height());
    }
}
function delBorderBottom(obj){
    obj.find("li:last").css("borderBottom","0");
}
function doAPILogin(strName){
    var iTop = (window.screen.availHeight-350)/2;
    var iLeft = (window.screen.availWidth-500)/2;
    var A=window.open("/api/"+strName,"TencentLogin","width=850,height=620,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1,top="+iTop+",left="+iLeft);
}

$(function(){
    $(".gx-gift-click").click(function(){
        $(".gx-gift-weixin").show();
        $(".gx-gift-click").hide();
     })
     $(".gx-gift-weixin .close").click(function(){
        $(".gx-gift-click").show();
        $(".gx-gift-weixin").hide();
     })
});



$(function () {  
	$(window).scroll(function(){ 
		 
		if ($(window).scrollTop()>100){  
			$(".side_ico1").css("display","block"); 
		}  
		else  
		{  
			$(".side_ico1").css("display","none");  
		}  
	});  

	//当点击跳转链接后，回到页面顶部位置  
	$(".side_ico1").click(function(){  
		//$('body,html').animate({scrollTop:0},1000);  
		if ($('html').scrollTop()) {  
			$('html').animate({ scrollTop: 0 }, 1000);  
			return false;  
		}  
		$('body').animate({ scrollTop: 0 }, 1000);  
		return false;              
   }); 
   
   //二维码
	$('.side_ico3, .side_ico4').mouseover(function(){
		$(this).find('img').toggle();	
	});
	$('.side_ico3, .side_ico4').mouseout(function(){
		$(this).find('img').hide();	
	});       
});     