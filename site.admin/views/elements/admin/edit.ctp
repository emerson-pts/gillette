<?php 
echo $this->element($form_file,
	array(
		'form_title' => (!empty($setup['edit_title']) ? $setup['edit_title'] : 'Alteração').': '.(!empty($setup['displayField']) &&  isset($dados_originais[$model][$setup['displayField']]) ? $dados_originais[$model][$setup['displayField']] : '').' (cód. '.$this->params['pass'][0].')',
		'form_submit_label' => 'Atualizar',
	));
