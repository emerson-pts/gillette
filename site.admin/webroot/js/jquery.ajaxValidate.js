// Return a boolean value telling whether // the first argument is a string. 
function isString() {
	if(typeof arguments[0] == 'string')return true;
	if(typeof arguments[0] == 'object'){
		var criterion = arguments[0].constructor.toString().match(/string/i);
		return (criterion != null);
	}
	return false;
}

function formErrorRemove(e_this){
	//se mandou string, então seleciona o elemento jquery
	if (isString(e_this) == false)var e_this = $(e_this);
	
	$(e_this)
		.removeClass('form-error')
		.parents('div')
		.removeClass('error')
		.find('div.error-message')
		.slideUp('normal',function(){
			$(this).remove();
		});
}

function formErrorAdd(e_this, message){
	//se mandou string, então seleciona o elemento jquery
	if (isString(e_this) == false)var e_this = $(e_this);
	
	$(e_this)
		.addClass('form-error')
		.parent()
		.addClass('error')
		.append('<div class="error-message hidden">' + message + '</div>')
		.find('div.error-message')
		.slideDown();
}

var timeoutId = null;
var lastCheckValue = null;
var lastCheckField = null;

function ajaxValidate(e_this){
	if (e_this.val() == lastCheckValue && e_this.attr("name") == lastCheckField)return true;
	
	lastCheckValue = e_this.val();
	lastCheckField = e_this.attr("name");
	
	$.ajax({
		async: false,
		type: "POST",
		data: e_this.closest("form").serialize(),
		url: ajaxValidatorUrl + e_this.attr("name"),
		dataType: "json",
		success: function(data){
			//Remove eventuais mensagens de erro
			formErrorRemove(e_this);

			if (data === false){
				alert("Erro na solicitação de validação");
			}else if (data === true){
				e_this.addClass("form-valid");
				return true;
			}else{
				e_this.removeClass("form-valid");
				formErrorAdd(e_this, data);
			}
		}
	});
}
$(document).ready(function() {
	$("input.ajaxValidate").bind("keyup change blur" , function(event){
		clearTimeout(timeoutId);
		timeoutId = setTimeout(ajaxValidate, 800, $(this));
	});

	$('.form-error').focus(function(){
		formErrorRemove($(this));
	});
});
