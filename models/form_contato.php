<?php
class FormContato extends AppModel {

	var $name = 'FormContato';
	var $useTable = false;
		
	var $validate = array(
		'nome' => array(
			'rule' => 'notEmpty', 
			'required'	=> true,
			'allowEmpty'=>false,
			'message' => "Campo Obrigatório",
		),
		'mensagem' => array(
			'rule' => 'notEmpty', 
			'required'	=> true,
			'allowEmpty'=>false,
			'message' => "Campo Obrigatório",
		),
	);
}