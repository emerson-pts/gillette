//menu
$(function(){
	try{
		$("a[rel^='prettyPhoto']").prettyPhoto();
	}catch(e){}
	
	$("ul#menu>li").hover(
		function(){
		    if($(this).find('ul').size() == 0 || $(this).find('ul').is(':hidden')){
		    	$(this).parents('ul').find('li.active>ul').hide();
		    	$(this).find("ul").show();
		    }		    
		  }, 
		  function(){
		    if(!$(this).hasClass('active')){
		    	$(this).find("ul").hide();
		    	$(this).parents('ul').find('li.active>ul').show();
		    }
		  }
	);
	
	jQuery("#cursos-list li,.qtip").each(function(index,element){
		$(this).qtip({
		      content: $(this).find("div.hide").html(),
		      style: {
		        padding: 5,
		        background: '#ffffff',
		        border: {
		          width: 4,
		          color: '#be2030'
		        },
		        color: '#666',
		        textAlign: 'left',
		        tip: 'bottomMiddle',
		        width: {
		          max: 400
		        }
		      },
		      hide: { when: 'mouseleave', fixed: true },
		      position:{
		        corner: {
		           target: 'topMiddle',
		           tooltip: 'bottomMiddle'
		        }
		      }
		});
	});
	
	var cursos_width=jQuery('#cursos-list li').size()*79;
	var cursos_animating=false;
	
	
	jQuery('div.cursos a.left').click(function(){
		if(parseInt(jQuery('#cursos-list').css('marginLeft'))>-(cursos_width-474) && cursos_animating==false){
			cursos_animating==true;
			$('#cursos-list').animate({marginLeft:'-=237'},500,function(){cursos_animating==false});
		}
	});
	
	jQuery('div.cursos a.right').click(function(){
		if(parseInt(jQuery('#cursos-list').css('marginLeft'))<0  && cursos_animating==false){
			cursos_animating==true;
			$('#cursos-list').animate({marginLeft:'+=237'},500,function(){cursos_animating==false});
		}
	});
	
	
/*	
	var pos = $("#menu").position();
	$("#menu li ul").each(function(){
		$(this).css({left:pos.left,top:pos.top+60});
	});
*/
	
	$('input.dateMask').mask('99/99/9999');
	//$("a.galeria-link-texto").bind("click",function(e){

	//$(e.target).parent.les('div.galeria-texto').css('background-color','red'); 
	
	//});
	
});