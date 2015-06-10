function blockUIpage(msg){
	if(msg == undefined)msg = 'Aguarde ...';
	$.blockUI({
		message: msg,
		css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-moz-border-radius': '10px',
			'-webkit-border-radius': '10px',
			'-webkit-background-clip': 'padding-box',
			'border-radius': '10px',
			opacity: .8, 
			fontSize:'18px',
			color: '#fff',
			display: 'block'
		}
	});
}

function blockUImsg(type, title, msg, duration){
	//type erro ou success
	var msg = '<div class="growlUI growlUI' + type + '"><h1>' + title + '</h1>' + (msg != undefined ? '<h2>' + msg + '</h2>' : '') + '</div>';
	
	if(duration == undefined) duration = 3000;
	
	$.blockUI({
		message: msg, 
		fadeIn: 700, 
		fadeOut: 700, 
		timeout: duration, 
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
			'-moz-border-radius': '10px',
			'-webkit-border-radius': '10px',
			'-webkit-background-clip': 'padding-box',
			'border-radius': '10px',
			opacity: .9, 
			color: '#fff',
			'z-index': 999999
		} 
	});
}