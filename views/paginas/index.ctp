<?php 
   // define a class da linha
	switch ($this->params['pass'][0]){
    case 'aovivo':
   		$class = 'aovivo';
		break;
    case 'informacoes':
       $class = 'informacoes';
        break;
	default:
	   $class = 'paginas';
	}

?>
<div class="wrap">
	<!--<div class="content_top"></div>-->
	<div class="content_middle">
		<div class="title-paginas">
			<div class="linha-title-paginas"></div>
				<center>
					<span>
						<?php echo $this->Html->image("deco_title_left.jpg", array("alt" => ""));?>
						<?php echo $pagina['Pagina']['titulo']; ?>
						<?php echo $this->Html->image("deco_title_right.jpg", array("alt" => ""));?>

					</span>
				</center>
			
		</div>
		<?php
		echo $this->Html->div('conteudo-paginas',
			(!empty($pagina['Pagina']['image']))?$this->Html->image('/'.$pagina['Pagina']['image'],array('alt'=>$pagina['Pagina']['image_legenda'],'title'=>$pagina['Pagina']['image_legenda'],'style'=>!empty($pagina['Pagina']['image_align'])?'float:'.$pagina['Pagina']['image_align']:false)):''.
			$pagina['Pagina']['conteudo'].'<br /><br />'
		);	
	?>
	</div><!-- content_middle -->
<br />
<br />
<br />
<div class="linha"></div>
</div>
