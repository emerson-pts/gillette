<a href="<?php echo @$vitrine['url'];?>"><img src="<?php echo @$vitrine['imagem'];?>" title="<?php echo @nl2br($vitrine['titulo']);?>"></a>
<div class="vitrine-main-item-desc"><?php
	if(!empty($vitrine['data_inicio'])){
		echo $this->Html->para('data',$vitrine['data_inicio']);
	}
	if(!empty($vitrine['titulo'])){
		echo $this->Html->link($vitrine['titulo'], @$vitrine['url'], array('target' => '_self','class'=>'titulo'));
	}
	if(!empty($vitrine['chamada'])){
		echo $this->Html->para('chamada',$vitrine['chamada']);
	}
?></div>