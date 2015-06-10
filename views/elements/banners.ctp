<?php
// ARROUBA BANNER - Lateral (300x250 pixels)

if($area=="sidebar_arroba"){
	$banner = $banners['sidebar_arroba']['Banner'];
	?><div class="arroba"><?php
		if(substr($banner["imagem"],strlen($banner["imagem"])-3,3)=="swf"){
			?><object height="250" width="300" type="application/x-shockwave-flash" data="<?=$this->base.'/'.$banner["imagem"]?>">
			<param value="<?=$this->base.'/'.$banner["imagem"]?>" name="movie"/>
			<param value="transparent" name="wmode"/>
			<param value="true" name="allowfullscreen"/>
			<param value="always" name="allowscriptaccess"/>
			<param value="high" name="quality"/>
			</object><?php
		}else{
			//$banner["sidebar_arroba"]
			echo $this->Html->image('../'.$banner['imagem'], array('url' => $banner['url'], 'alt' => $banner['titulo']));
		}
	?></div><?php
}else if($area=="footer"){
	?><div class="anuncios"><?php
	foreach($banners['footer'] AS $banner){
		$banner = $banner['Banner'];
		?><div class="anuncio-area"><?php
		if(substr($banner["imagem"],strlen($banner["imagem"])-3,3)=="swf"){
			?><object height="100" width="300" type="application/x-shockwave-flash" data="<?=$this->base.'/'.$banner["imagem"]?>">
			<param value="<?=$this->base.'/'.$banner["imagem"]?>" name="movie"/>
			<param value="transparent" name="wmode"/>
			<param value="true" name="allowfullscreen"/>
			<param value="always" name="allowscriptaccess"/>
			<param value="high" name="quality"/>
			</object><?php
		}else{
			echo $this->Html->image('../'.$banner['imagem'], array('url' => $banner['url'], 'alt' => $banner['titulo']));
		}
		?></div><?php	
	}
	?><div class="clear"></div></div><?php
}
?>