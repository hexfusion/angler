function search_clear()
{
document.getElementById("search_clear1").value = "";
}

function news_clear()
{
document.getElementById("news_clear1").value = "";
}

function img_change1()
{
document.getElementById("read_image").src = "images/banner_read_bg2.png";
}
function img_change2()
{
document.getElementById("read_image").src = "images/banner_read_bg1.png";
}

function img_change3()
{
document.getElementById("read_image1").src = "images/journey_more_bg2.png";
}
function img_change4()
{
document.getElementById("read_image1").src = "images/journey_more_bg1.png";
}

$(document).ready(function(){
   setInterval(function(){
      if ($(".activei").css("marginRight")=='0px'){
        $("#slide_detail_one1").hide();
		$("#slide_detail_hover1").show();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activei").css("marginRight")!='0px'){
         
         $("#slide_detail_one1").show();
		$("#slide_detail_hover1").hide();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activej").css("marginRight")=='0px'){
        $("#slide_detail_one2").hide();
		$("#slide_detail_hover2").show();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activej").css("marginRight")!='0px'){
         
         $("#slide_detail_one2").show();
		$("#slide_detail_hover2").hide();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activek").css("marginRight")=='0px'){
        $("#slide_detail_one3").hide();
		$("#slide_detail_hover3").show();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activek").css("marginRight")!='0px'){
         
         $("#slide_detail_one3").show();
		$("#slide_detail_hover3").hide();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activel").css("marginRight")=='0px'){
        $("#slide_detail_one4").hide();
		$("#slide_detail_hover4").show();
      }
   }, 0);
});

$(document).ready(function(){
   setInterval(function(){
      if ($(".activel").css("marginRight")!='0px'){
         
         $("#slide_detail_one4").show();
		$("#slide_detail_hover4").hide();
      }
   }, 0);
});



 $(function() {
                var current = 1;
                
                var iterate		= function(){
                    var i = parseInt(current+1);
                    var lis = $('#rotmenu').children('li').size();
                    if(i>lis) i = 1;
                    display($('#rotmenu li:nth-child('+i+')'));
                }
                display($('#rotmenu li:first'));
                var slidetime = setInterval(iterate,6000);
				
				
                $('#rotmenu li').bind('mouseover',function(e){
				 display($(this));
				 e.preventDefault();
				});
			
				function display(elem){
                    var $this 	= elem;
                    var repeat 	= false;
                    if(current == parseInt($this.index() + 1))
                        repeat = true;
						
					if(!repeat)
                        $this.parent().find('li:nth-child('+current+') a' ).stop(true,true).animate({'marginRight':'-20px','width':'245px' },0,function(){
                            $(this).animate({'opacity':'1'},700);
							$(this).css("background-image", "url(images/flash_menu_bg.gif)").css('border', 'solid 1px #c6c6c6'); 
						 });
					
					current = parseInt($this.index() + 1);
					
                    var elem = $('a',$this);
					elem.stop(true,true).animate({'marginRight':'0px','opacity':'1.0','width':'295px'},0).css("background-image", "url(images/flash_menu_bg.png)").css("border","none"); 
					 var info_elem = elem.next();
					
                    $('#rot1 .heading').animate({'left':'-420px'}, 500,'easeOutCirc',function(){
                        $('h1',$(this)).html(info_elem.find('.info_heading').html());
                        $(this).animate({'left':'0px'},400,'easeInOutQuad');
                    });
					
                    $('#rot1 .description').animate({'bottom':'-270px'},500,'easeOutCirc',function(){
                        $('p',$(this)).html(info_elem.find('.info_description').html());
                        $(this).animate({'bottom':'0px'},400,'easeInOutQuad');
                    })
                    $('#rot1').prepend(
                    $('<img/>',{
                        style	:	'opacity:0',
                        className : 'bg'
                    }).load(
                    function(){
                        $(this).animate({'opacity':'1'},0);
                        $('#rot1 img:first').next().animate({'opacity':'0'},0,function(){
                            $(this).remove();
                        });
                    }
                ).attr('src','images/'+info_elem.find('.info_image').html()).attr('width','720').attr('height','401')
                );
                }
            });