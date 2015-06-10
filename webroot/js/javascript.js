function blockUIpage(msg){
	if(msg == undefined)msg = 'Aguarde ...';
	$.blockUI({
		message: msg,
		css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			opacity: .8, 
			fontSize:'18px',
			color: '#fff',
			display: 'block'
		}
	});
}

function blockUImsg(type, title, msg){
	//type erro ou success
	var msg = '<div class="growlUI growlUI' + type + '"><h1>' + title + '</h1><h2>' + msg + '</h2></div>';
	
	$.blockUI({
		message: msg, 
		fadeIn: 700, 
		fadeOut: 700, 
		timeout: 3000, 
		showOverlay: false, 
		centerY: false, 
		css: { 
			width: '350px', 
			top: '10px', 
			left: '', 
			display: 'block',
			right: '10px', 
			border: 'none', 
			padding: '5px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			opacity: .9, 
			color: '#fff' 
		} 
	});
}

function object_length(myobj){
	var count = 0;
	for (k in myobj) if (myobj.hasOwnProperty(k)) count++;
	return count;
}

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
