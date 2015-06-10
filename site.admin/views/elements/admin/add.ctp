<?php 
echo $this->element($form_file,
	array(
		'form_title' => (!empty($setup['add_title']) ? $setup['add_title'] : 'Novo Cadastro'),
		'form_submit_label' => 'Cadastrar',
	));

