<!-- colununa de conteúdo -->
<div class="col-left">
	<div class="category-top">
		<div class="category"><?php echo ucfirst($this->params["pass"][0]);?></div>
		<dir class="category-intro">Abaixo, você confere os nossos <?php echo $this->params["pass"][0];?></dir>
	</div>
	
	<div class="<?php echo $this->params["pass"][0];?>"><?php
		$i=1;
		foreach($banners as $banner){
			?><div<?php if($i%4==0){ ?> class="last"<?php } ?>><?php
			echo $this->Html->image('../'.$banner["Banner"]["imagem"],array(
				"alt" => $banner["Banner"]["titulo"],
				'border'=>'0',
				'url'=>(!empty($banner["Banner"]["url"])?$banner["Banner"]["url"]:false),
			));
			?></div><?php
			$i++;
		}
		
	?><br class="clear" />
	</div>

</div>
<!-- colununa de conteúdo -->
