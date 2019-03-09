// JavaScript Document
$(function(){

	// 幻灯
	function banFocus(obj,obtn){
		var len = $(obj).find('li').length, index = 0;
		function show(index){
			$(obtn).eq(index).addClass('act').siblings().removeClass('act')
			$(obj).find('li').eq(index).fadeIn(900).siblings().fadeOut(400);
		}
		$(obtn).mouseenter(function(){
			var index = $(this).index();
			show(index);
		}).eq(0).trigger('mouseenter')
		$(obj).hover(function(){
			clearInterval(timer);
		},function(){
			timer = setInterval(function(){
				index++;
				if(index == len){ index = 0}
				show(index);
			},4500)
		}).trigger('mouseleave');
	}
	
	banFocus('#focus-box','#focus-btn a');
	
	//独代大作
	function onlyFun(onObj, onBox){
		$(onObj).hover(function(){
			$(this).find(onBox).stop().animate({bottom:'0'},400);	
		},function(){
			$(this).find(onBox).stop().animate({bottom:'-184px'},400);	
		});
	}
	onlyFun('.only_li ul li','.only_txt');	
	
	//游戏展示样式
	var gameLen = $('.game_li ul li');
	for(var i=0; i<gameLen.length; i++){
		if((i+1)%3 == 0){
			gameLen.eq(i).css('margin-right',0);	
		}	
	}
	
	//快速通道样式
	var fastLen = $('.fast_con a');
	for(var i=0; i<fastLen.length; i++){
		if((i+1)%2 == 0){
			fastLen.eq(i).css('border-right',0);	
		}	
	}
	
	//开服动态
	(function(){
		//样式初始化
		this.init = function(){
			$('.m_ser_til a:first').addClass('on');
			$('.m_ser_eg:first').show();
			$('.m_ser_eg').each(function(){
				$(this).find('.m_ser_egli').first().show();
			});
			$('.m_ser_ico').each(function(){
				$(this).find('i').first().addClass('on');
			});
		}
		
		//翻页
		this.doPage = function(){
			$('.m_ser_ico i').click(function(){
				$(this).addClass('on').siblings().removeClass('on');
				var newInd2 = $(this).index();
				$(this).parents('.m_ser_ico').siblings('.m_ser_egcon').find('.m_ser_egli').hide().eq(newInd2).show();	
			});
		}
		
		//小导航切换
		this.checkTab = function(){
			$('.m_ser_til a').hover(function(){
				$(this).addClass('on').siblings().removeClass('on');
				var newInd = $(this).index();
				$('.m_ser_eg').hide().eq(newInd).show();
			});
		}
		
		//为每行开服添加鼠标滑过事件
		this.mouseEvent = function(){
			$('.m_ser_egli').each(function(){
				$(this).find('.m_ser_egdec').first().find('p').hide();
				$(this).find('.m_ser_egdec').first().find('.m_ser_kf').show();
				
				$('.m_ser_egdec').hover(function(){
					$(this).find('p').hide();
					$(this).siblings().find('p').show();	
					$(this).find('.m_ser_kf').show();
					$(this ).siblings().find('.m_ser_kf').hide();
				});
			})	
		}
		
		this.init();
		this.checkTab();
		this.mouseEvent();
		this.doPage();	
	})();
	
	//底部微信
	$('.f_wx').hover(function(){
		$(this).find('img').show();	
	},function(){
		$(this).find('img').hide();	
	});

    //登录
    (function(){
        //初始化
        $('.log_eg:first').show();
        $('.log_note').html('扫码登录在这里');

        $('.log_ico').click(function(){
            var flag = $(this).hasClass('log_ico_h');
            //alert(flag);
            $(this).removeClass('log_ico_h');
            $('.log_note').html('扫码登录在这里');
            $('.log_eg').hide().eq(0).show();
            if(!flag){
                $(this).addClass('log_ico_h');
                $('.log_note').html('密码登录在这里');
                $('.log_eg').hide().eq(1).show();
            }
        });
        $('.log_pic').hover(function(){
            $('.log_pho').show();
        },function(){
            $('.log_pho').hide();
        });
    })();

});