<?php
if(!isset($setup['topLinkLeft']))$setup['topLinkLeft'] = array();
$setup['topLinkLeft'][$this->Html->image('icons/fugue/navigation-180.png').' Voltar'] = array('url' => $this->params['named'] + array('action' => 'index'), 'htmlAttributes' => array('escape' => false));
$this->set('setup', $setup);
?>
<section class="grid_12">
	<div class="block-border"><div class="block-content">
		<?php
		 echo $this->Element('admin/form/default_form', 
			array(
				'form_title' => $form_title,
				'form_submit_label' => $form_submit_label,
			)
		);
		?>
	</div></div>
</section>