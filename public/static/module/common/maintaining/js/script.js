var slider;
var images = [
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/ground.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/clouds.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/ground-bottom.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/hole.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/mountain.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/rocks.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/sky-bg.jpg",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/sweat.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/texty-sign.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/worker.png",
"http://onegame.yuan-jian.com/static/module/common/maintaining/images/working-sign.png"
];

var index = 0;
var transitionSpeed = 500;
var imageIntervals = 5000;

var startIntervals;
var intervalSetTime;
var contentOpen = false;

$(document).ready(function() {
    


           
                                         
	
    $(function() {
		
        $.preload(images, {
            init: function(loaded, total) {
            		
            },
			
            loaded_all: function(loaded, total) { 
             
                            
                $('#indicator').fadeOut('slow', function() {
                   
                    function swing(){
                                   if($('html').hasClass('ie')){
                                   if(!$('.swing').hasClass('down')){
                                   
                           $('.swing').animate({rotate:'190deg', left: 330,
    top: 430},1500,function(){
    $('.swing').toggleClass('down');
    });    
                                   }else{
                                   
                                    $('.swing').animate({rotate:'40deg', left: 190,
    top: 35},1500,function(){
    $('.swing').toggleClass('down');
    });    
                                   
                                   }}else{
                                   $('.swing').toggleClass('right');
                                   }
                       
         
                        $('.sweat').toggleClass('show');
                        
                        setTimeout(swing, 1500);
                    }
                    swing(); 
        
                   
                    $('#clouds').pan({
                        fps: 30, 
                        speed: 1.8, 
                        dir: 'left', 
                        depth: 300
                    });
                     
                   
                                
                    $('.init').fadeIn(function(){
                        $(this).removeClass('init');
                      
                            
                        setTimeout(function(){
                            
                            $('.mountain').plaxify({
                                "xRange":20,
                                "yRange":0
                            }) ;
                                     
                                        
                              
                            $.plax.enable(); 
                            
                           
 
         
                            
                        },500)   
                                  
                                  
                                      
                                       
                    })
                                         
                                        
                                        
					
                });
            }
        });
    });
 
 

})
