<?php
class Sitemap extends AppModel {
    var $name = 'Sitemap';
	var $displayField = 'label';

    var $actsAs = array('TreePlus');
    var $order = 'Sitemap.lft ASC';

    var $validate = array(
		'label' => array(
			'minLength' => array(
			   'rule'=>array('minLength',1), 
				'required'=>true,  
				'allowEmpty'=>false,
				'message'=>'Por favor, digite o rótulo.', 
			),
			'friendly_url' => array(
				'rule' => 'friendly_url_validate',
				'required'=>true,
				'allowEmpty'=>false,
				'message'=> 'Não foi possível gerar uma url amigável.',
			),
		),
		
		'friendly_url' => array(
			'rule' => 'isUnique',
			'message'=> 'A URL amigável deve ser única.',
		),
	);
 
	function load($id = null){
//		$menu = $this->children(array('id' => $id, 'direct' => true, 'recursive' => true, 'fields' => array('id', 'label', 'friendly_url', 'show_footer',)));
		$menu = $this->find('all', array('conditions' => array('parent_id' => $id, 'status' => '1',),));
		foreach($menu AS $key=>$value){
			$menu[$key]['submenu'] = $this->load($value['Sitemap']['id']);
		}
		return $menu;
	}

	function setupAdmin($action = null, $id = null){
  		//Monta opções de Referência
        if(!empty($id))$current_path = $this->getfullpath($id);
				        
        $this->options = array(
			'parent_id' => $this->generatetreelist($conditions=null, $keyPath=null, $valuePath=null, $spacer= '_', $recursive=null),
			'status'	=> array('0' => 'Desabilitado', '1' => 'Habilitado'),
		);
				
		foreach($this->options['parent_id'] AS $key=>$value){
            $path = $this->getfullpath($key);
            
            if(empty($current_path) || !preg_match('/^'.preg_quote($current_path, '/').'/', $path))
                $this->options['parent_id'][$key] = $path;
            else
                unset($this->options['parent_id'][$key]);
		}

		//Seta flag para automaticamente incluir o full path no find
		$this->afterFindGetfullpath = array('id', $this->displayField,);

        $setupAdmin = array(
			'displayField' => $this->displayField,
			'displayFieldTreeIndex' => 'label',
			
			'pageDescriptionIndex' => __('Arraste os itens para reposicioná-los. Para acessar o menu de opções, passe o mouse sobre eles.', true),
			
            'topLink' => array(
				'Novo menu' => array('url' => array('controller' => 'sitemaps', 'action' => 'add'), 'htmlAttributes' => array()),
			),
			
			'defaultLimit' => 999999,


			'form'	=> array(
				'parent_id'		=> array('label' => 'Referência', 'empty' => '--Raiz--', 'options' => $this->options['parent_id'],),
				'label'			=> array('label' => 'Rótulo',),
				'friendly_url'	=> array('label' => 'Url Amigável', 'type' => 'text', 'after' => '&nbsp;<small>Deixe em branco, caso queira defini-la automaticamente.</small>',),
				'route'			=> array('label' => 'Rota', 'type' => 'text', 'after' => '&nbsp;<small>Controller/action que deve ser executado ao acessar o caminho.</small>',),
				'show_footer'	=> array('label' => 'Exibir no rodapé', 'type' => 'checkbox', 'class'=>'switch', 'default' => '1', 'value' => '1'),
				'status'		=> array('label' => 'Status', 'type' => 'checkbox', 'class'=>'switch', 'default' => '1', 'value' => '1'),
			),
		);
		
		return $setupAdmin;
	}  
}