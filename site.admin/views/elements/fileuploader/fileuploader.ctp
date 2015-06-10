<?php
//Default
$fileuploader += array(
	'showMessage' 	=> 'alert(message);',
	'id'			=> 'fileuploader',//Para desativar a habilitação automática do fileuploader, envie o índice id como false.
	'url'			=> $this->params['url']['url'],
	'params'		=> false,
    'maxConnections'=> 3,
	'sizeLimit'		=> $this->Webjump->return_bytes($this->Webjump->return_bytes(ini_get('post_max_size')) < $this->Webjump->return_bytes(ini_get('upload_max_filesize')) ? $this->Webjump->return_bytes(ini_get('post_max_size')) : $this->Webjump->return_bytes(ini_get('upload_max_filesize'))),
	'minSizeLimit'	=> 0,                             
	'onSubmit'		=> '',//function(id, fileName)
	'onProgress'	=> '',//function(id, fileName, loaded, total)
	'onComplete'	=> '',//function(id, fileName, responseJSON)
	'onCancel'		=> '',//function(id, fileName)
	'template'		=> "'<div class=\"qq-uploader\">' + 
                '<div class=\"qq-upload-drop-area\"><span>Solte os arquivos aqui para enviá-los</span></div>' +
                '<div class=\"qq-upload-button\">Enviar arquivos</div>' +
                '<ul class=\"qq-upload-list\"></ul>' + 
             '</div>'",
	'fileTemplate'	=> "'<li>' +
                '<span class=\"qq-upload-file\"></span>' +
                '<span class=\"qq-upload-spinner\"></span>' +
                '<span class=\"qq-upload-size\"></span>' +
                '<a class=\"qq-upload-cancel\" href=\"#\">Cancelar</a>' +
                '<span class=\"qq-upload-failed-text\">Falha</span>' +
            '</li>'",        
        
);
echo $this->Html->script(array('fileuploader/fileuploader',), array('inline' => false));
echo $this->Html->css(array('fileuploader/fileuploader',), $rel = null, array('inline' => false));



//Se o id está diferente de vazio
if(!empty($fileuploader['id'])):
?>
<div id="fileuploader_temp" class="hidden"></div>
<script>
jQuery(document).ready(function(event){
	var fileuploader = jQuery('#<?php echo $fileuploader['id'];?>');//seleciona o elemento

	//Se não tem o elemento
	if(fileuploader.length == 0){
		//Inclui elemento no html
		jQuery('#fileuploader_temp').replaceWith('<div id="<?php echo $fileuploader['id'];?>"></div>');
		
		//Seleciona o elemento
		var fileuploader = jQuery('#<?php echo $fileuploader['id'];?>');
	}
	
	//Ativa o upload
	var uploader = new qq.FileUploader({
		// pass the dom node (ex. $(selector)[0] for jQuery users)
		element: jQuery('#<?php echo $fileuploader['id'];?>')[0],
		// path to server-side upload script
		action: "<?php echo $fileuploader['url'];?>",
		<?php
		if(!empty($fileuploader['params'])){
			echo 'params: '.$this->Javascript->value($fileuploader['params']).',';
		}
		?>

		allowedExtensions: [<?php if(is_string($fileuploader['extensions'])){echo $fileuploader['extensions'];}else{echo "'".implode("','", $fileuploader['extensions'])."'";}?>],
		sizeLimit: <?php echo $fileuploader['sizeLimit'];?>,
		maxConnections: <?php echo $fileuploader['maxConnections'];?>,

        messages: {
            typeError: "{file} é inválido. Somente são permitidos arquivos do tipo: {extensions}.",
            sizeError: "{file} é muito grande, o tamanho máximo do arquivo é {sizeLimit}.",
            minSizeError: "{file} é muito pequeno, o tamanho mínimo é {minSizeLimit}.",
            emptyError: "{file} é vazio, por favor selecione os arquivos novamente sem este arquivo.",
            onLeave: "Os arquivos estão sendo enviados. Se você sair da página, o upload será cancelado."            
        },
        showMessage: function(message){
        	<?php echo $fileuploader['showMessage'];?>
        },
        
        template: <?php echo $fileuploader['template'];?>,
             
        fileTemplate: <?php echo $fileuploader['fileTemplate'];?>,
             
		onSubmit: function(id, fileName){<?php echo $fileuploader['onSubmit'];?>},
		onProgress: function(id, fileName, loaded, total){<?php echo $fileuploader['onProgress'];?>},
		onComplete: function(id, fileName, responseJSON){<?php echo $fileuploader['onComplete'];?>},
		onCancel: function(id, fileName){<?php echo $fileuploader['onCancel'];?>}

	}); 
});
</script>
<?php 
endif;