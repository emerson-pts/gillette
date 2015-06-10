//Impede o voltar da página indevido
$(document).keydown(function(e) {
	var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
	var element = node.nodeName.toLowerCase();
	if (element != 'input' && element != 'textarea') {
		if (e.keyCode === 8) {
			return false;
		}
	}
});

$(document).keypress(function(e) {
	var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
	var element = node.nodeName.toLowerCase();
	if (e.keyCode === 13 && (element == 'input' || element == 'select')) {
		if($(node).hasClass('disable-next-field-js')){
			return true;
		}
		
		if($(node).hasClass('submit-on-enter')){
			$(node).parents('form:eq(0),body').trigger('submit');
		}
		
		if(element == 'input' && node.type == 'submit'){
			return false;
		}
		
		var fields = $(node).parents('form:eq(0),body').find('button, input, textarea, select').filter(':visible');
		var index = fields.index( node );
		if ( index > -1 && ( index + 1 ) < fields.length ) {
			var field = fields.eq( index + 1 );
			field
				.trigger('focus')
			;
			
			var windowHeight = $(window).height();
			var scrollTop = $(window).scrollTop();
			var fieldTop = field.offset().top;
			if((windowHeight + scrollTop - 100) < fieldTop ){
				jQuery('html, body').animate({
					scrollTop: scrollTop + 100
				}, 500);
			}
				
		}
		return false;
	}
});
/*
$(document).ready(function(){
	$("input,select").not( $(":button,:submit") ).keypress(function (evt) {
		if (evt.keyCode == 13) {
			if ($(this).attr('type') != 'submit'){
				var fields = $(this).parents('form:eq(0),body').find('button, input, textarea, select').filter(':visible');
				var index = fields.index( this );
				if ( index > -1 && ( index + 1 ) < fields.length ) {
					fields.eq( index + 1 ).focus();
				}
				return false;
			}
		}
	});
});
*/
/*
//Desativa tecla enter no formulario, backspace, dá um tab no Enter
function checkCR_down(evt) {
//	var evt  = (evt) ? evt : ((event) ? event : null);
//	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
//	if (evt.keyCode == 8 && node.type == undefined){
//	    	return false;
//	}
}

function checkCR_press(evt) {
	var evt  = (evt) ? evt : ((event) ? event : null);
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
	if (evt.keyCode == 8 && node.type == undefined){
    	return false;
	}else if(evt.keyCode == 13 && (node.type == undefined || (node.type!="textarea" && node.type != "submit" && node.type !="button" && node.type !="image" && node.type != "reset"))) {
		if ((window.autotab == undefined || window.autotab != 0) && node.className.indexOf('no_autotab') == -1){
			if (node.type != undefined){
				while (index_atual == undefined || (node != undefined && node.type == "hidden")){
					var index_atual = getIndex(node);
					node = node.form[index_atual+1];
					if (node != undefined && node.type != "hidden" && node.disabled != "false" && ((node.getAttribute('autotab') != null && node.getAttribute('autotab') != 'false' && node.getAttribute('autotab') != '0') || (node.type != "submit" && node.type != "reset"))){
						if(node.type == 'submit' || node.type == 'buttom' || node.type == 'image'){
							node.click();
							return false;
						}else{
							node.focus();
							return false;
						}
					}
				}
			}
		}
		//Não tem mais input para focar. False para não dar o submit automático
		return false;
	}
}

//acha o index de um campo no formulário
function getIndex(input) {
	var i = 0;
	while (i < input.form.length){
		if (input.form[i] == input){
			return i;
		}else{
			 i++;
		}
	}
	return false;
}

document.onkeydown = checkCR_down;
document.onkeypress = checkCR_press;
*/