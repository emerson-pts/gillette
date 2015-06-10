<?php
class ImageHelper extends Helper {

	//Verifica o tamanho da imagem
	//Retorna false em caso de erro
	//00 para largura e altura informada maiores que o original
	//10 para largura menor e altura maior que a imagem original
	//01 para largura maior e altura menor que a imagem original
	//11 para largura e altura menores que o original 
	
    function checkSize($file, $largura = 0, $altura = 0) {
    	//Ajusta caminho do arquivo
    	if(!preg_match('/^\//', $file)){
    		if(!preg_match('/^img\//', $file))
    			$file = WWW_ROOT .'img' . DS . $file;
    		else
    			$file = WWW_ROOT . $file;
		}
		
		//Lê dados da imagem
    	if(!$getimagesize = getimagesize($file))return false;
    	
    	$return = '';
    	//Se não é para checar a largura ou a largura é menor
    	if(empty($largura) || $largura >= $getimagesize[0])
    		$return .= '0';
    	else
    		$return .= '1';

    	//Se não é para checar a altura ou a altura é menor
    	if(empty($altura) || $altura >= $getimagesize[1])
    		$return .= '0';
    	else
    		$return .= '1';

		return $return;    	
    }
} 