<?php
class ResizeImg{	
	function resize($src = null, $size = null, $crop = null){
		ini_set("memory_limit","128M");
		$imgDir = WWW_ROOT;
		$cacheDir = WWW_ROOT.'img/thumb'.DS;
		$cacheUrl = $this->base.'/img/thumb/';
		$cacheTime = 1*(60*60*24); //1 dia
		if(empty($src)){
			return array('error' => 'Arquivo não informado');
		}
        
		if(empty($size)){
			return array('error' => 'Tamanho não informado');
		}
		$src = realpath($imgDir.$src);

		$ext = preg_replace('/^.*\./', '', $src);

		if(!$src || !preg_match('/^'.preg_quote($imgDir, '/').'/', $src)){
			return array('error' => 'Arquivo inválido');
		}

		$cacheFile = $cacheDir.preg_replace('/\.[^\.]+$/','',preg_replace('/^'.preg_quote($imgDir, '/').'/', '', $src)).'_'.$size.(!empty($crop) ? '_crop'.$crop : '').'.'.$ext;

		//Usa o arquivo de cache se ele existe e ainda não expirou
		if(is_file($cacheFile) && ($cacheTime === false || filemtime($cacheFile) >= time()- $cacheTime)){
			return array('file' => $cacheFile, 'url' => preg_replace('/([^\/]+)/e', 'rawurlencode("\1")', $cacheUrl.preg_replace('/^'.preg_quote($cacheDir, '/').'/', '', $cacheFile)));
		}

		if(!is_dir(dirname($cacheFile)) && !mkdir ( dirname($cacheFile),  $mode = 0777, $recursive = true)){
			return array('error' => 'Erro na criação do diretório de cache da miniatura.');
		}
		
		switch(strtolower($ext)){
			case 'jpg':
			case 'jpeg':
				$img_func = 'imagecreatefromjpeg';
				break;
				
			case 'gif':
				$img_func = 'imagecreatefromgif';
				break;
			
			case 'png':
				$img_func = 'imagecreatefrompng';
				break;
		}
		
		//Cria um objeto com a imagem original
		if (!$im = $img_func($src)){
			return array('error' => 'Imagem inválida');
		}
		
		//Lê o tamanho da imagem original
		$im_x = imagesx($im);
		$im_y = imagesy($im);

		//Se usou o formato larguraxaltura no parametro de tamanho, então verifica qual é o máximo permitido
		if (preg_match('/^([0-9]+)(x|\*)([0-9]+)$/',$size,$matches)){
			if(($matches[2] == 'x' && $matches[1]/$im_x < $matches[3]/$im_y) || ($matches[2] == '*' && $matches[1]/$im_x > $matches[3]/$im_y)){
				$size = 'w'.$matches[1];
			}else{
				$size = 'h'.$matches[3];
			}
		}
		

		//Define nova largura e altura
		switch (substr($size,0,1)){
			case 'w':
				$width = (int)substr($size,1);
				$im_output_x = $width;
				$im_output_y = ceil($im_y*($width/$im_x));
				break;
						
			case 'h':
				$height = (int)substr($size,1);
				$im_output_x = ceil($im_x*($height/$im_y));
				$im_output_y = $height;
				break;
		}
		
		
		//Verifica se a imagem precisa ser redimensionada
		$resize = !($im_x < $im_output_x || $im_y < $im_output_y || ($im_x == $im_output_x && $im_y == $im_output_y));
		
		//Se não precisa redimensionar e não é para dar crop, somente copia a imagem original
		if(!$resize && empty($crop)){
			if(copy($src, $cacheFile))
				return array('file' => $cacheFile, 'url' => $cacheUrl.preg_replace('/^'.preg_quote($cacheDir, '/').'/', '', $cacheFile));
			else
				return array('error' => 'Erro na cópia do arquivo sem redimensionamento.');

		}
		//Se a imagem deve ser redimensionada
		else if($resize){
			//Cria nova imagem
			if (!$im_output = imagecreatetruecolor($im_output_x, $im_output_y)){
				return array('error' => 'Erro na geração da miniatura');
			}
			
			if(function_exists('imageantialias'))imageantialias($im_output,1);


			//Salva transparência
			imagealphablending($im_output, false);
			imagesavealpha($im_output,true);
			$transparent = imagecolorallocatealpha($im_output, 255, 255, 255, 127);
			imagefilledrectangle($im_output, 0, 0, $im_output_x, $im_output_y, $transparent);

	
			//Copia imagem original na nova redimensionada
			if(!imagecopyresampled ($im_output, $im, 0, 0, 0, 0, $im_output_x, $im_output_y, $im_x, $im_y)){
				return array('error' => 'Erro no redimensionamento da imagem');
			}
		}
		//caso contrário, cria uma imagem de saída igual a original
		else{
			$im_output = $im;
		}
		
		
		//Se é para dar crop e o tamanho foi informado em larguraxaltura
		if(!empty($crop) && preg_match('/^([0-9]+)x([0-9]+)$/', $crop, $match_crop) && ($im_output_x > $match_crop[1] || $im_output_y > $match_crop[2])){
			if($match_crop[1] > $im_output_x)$match_crop[1] = $im_output_x;
			if($match_crop[2] > $im_output_y)$match_crop[2] = $im_output_y;
			
			$im_output_crop = imagecreatetruecolor($match_crop[1], $match_crop[2]);
			imagecopy($im_output_crop, $im_output, 0, 0, floor(($im_output_x/2)-($match_crop[1]/2)), floor(($im_output_y/2)-($match_crop[2]/2)), $match_crop[1], $match_crop[2]);
			$im_output = $im_output_crop;
		}

/*
		//Marca d'água se a imagem tem mais de 200 pixels
		if($im_output_x > 400 || $im_output_y > 250){
			$watermark_im = imagecreatefrompng(WWW_ROOT.'img/watermark.png');
			$watermark_space_x = 200;
			$watermark_space_y = 100;
			$padding = 10;
			
			$watermark_x = imagesx($watermark_im);
			$watermark_y = imagesy($watermark_im);
			
			$start_x = $padding;
			
			//Aplica marca d'água
			for($current_y = $padding; $current_y < $im_output_y; $current_y += $watermark_y + $watermark_space_y){
				for($current_x = $start_x; $current_x < $im_output_x; $current_x += $watermark_x + $watermark_space_x){
					imagecopy($im_output, $watermark_im, $current_x, $current_y, 0, 0, $watermark_x, $watermark_y);
				}
				$start_x = ($start_x == $padding ? $watermark_x/2 + $watermark_space_x/2 + $padding : $padding);
			}
		}
*/
		
		
		//Limpa status de existência ou não dos arquivos
		clearstatcache();

		switch(strtolower($ext)){
			case 'jpg':
			case 'jpeg':
				$img = imagejpeg($im_output, $cacheFile, 95);
				break;
				
			case 'gif':
				$img = imagegif($im_output, $cacheFile);
				break;
			
			case 'png':
				$img = imagepng($im_output, $cacheFile);
				break;
		}
	
		
		
		//Cria arquivo da miniatura e verifica se ele foi realmente criado
		if (!$img || !is_file($cacheFile)){
			return array('error' => 'Erro na geração do cache da miniatura.');
		}

		//Exibe imagem
		//Corrigir url
		return array('file' => $cacheFile, 'url' => preg_replace('/([^\/]+)/e', 'rawurlencode("\1")', $cacheUrl.preg_replace('/^'.preg_quote($cacheDir, '/').'/', '', $cacheFile)));
	}
}