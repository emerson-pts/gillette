<?php
if($_SERVER['REMOTE_ADDR'] == '::1')$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {
	
	var $helpers = array('Session', 'Html','Text', 'Form', 'Image', 'Formatacao', 'Js' => array('Jquery'), 'Cache', );
	var $components = array('RequestHandler', 'Session', 'Cookie',);
	var	$cacheAction = "1 day";

	var $uses = array('Sitemap', 'Configuration', 'Pagina','Calendario');
	
	function beforeFilter(){
		//Descomente a linha a seguir em sites multidiomas 
		//$this->_setLanguage();

		//Carrega configurações
		if(is_object($this->Configuration))$this->Configuration->load();
		
		//Desativa debug quando é uma requisição ajax
		if ( $this->RequestHandler->isRss() || $this->RequestHandler->isAjax() )Configure::write('debug',0);  

		//Força a action padrão aparecer no URL quando ela não aparecer na variável url
		if(!preg_match('/^'.preg_quote($this->params['controller'], '/').'\/'.$this->params['action'].'(\/|$)/', $this->params['url']['url'])){
			$this->params['url']['url'] = preg_replace('/^('.preg_quote($this->params['controller'], '\/?/').')(\/|$)/','\1/'.$this->params['action'].'/', $this->params['url']['url']);
		}
		
		if($this->params['url']['url'] == '/')$this->params['url']['url'] = $this->params['controller'].'/'.$this->params['action'];

/*
		//Se fez a rota pelo sitemap reescreve os parametros originais
		if(!empty($this->params['originalArgs'])){
			foreach($this->params['originalArgs'] AS $key=>$value){
				$this->$key = $value;
			}
		}
*/

		return true;
	}
	
	
	function beforeRender(){	
	
		if ($this->RequestHandler->isAjax())$this->set('isAjax', true);
		
		$menu=$this->Sitemap->load();
		
		//Define o menu ativo quando acesso a notícia
		if(empty($this->params['originalArgs']['passedArgs'])
			&& $this->name == 'Noticias'
			&& $menu_find = $this->Sitemap->find('first', array('conditions' => array('route' => 'noticias')))){
				$this->params['originalArgs']['passedArgs'][0] = $menu_find['Sitemap']['friendly_url'];
		}
		//Define o menu ativo quando acesso a contatos
		else if(empty($this->params['originalArgs']['passedArgs'])
			&& $this->name == 'Galerias'
			&& $menu_find = $this->Sitemap->find('first', array('conditions' => array('route' => 'galerias')))){
				$this->params['originalArgs']['passedArgs'][0] = $menu_find['Sitemap']['friendly_url'];
		}
		
		if(!empty($this->params['originalArgs']['passedArgs'])){
			$menu_ativo = Set::extract('/Sitemap[friendly_url='.$this->params['originalArgs']['passedArgs'][0].']', $menu);

			$submenu = $this->Sitemap->children(array('id' => $menu_ativo[0]['Sitemap']['id'], 'direct' => true, 'fields' => array('id', 'label', 'friendly_url')));
			if(!empty($submenu) && !empty($this->params['originalArgs']['passedArgs'][1])){
				if(isset($this->params['originalArgs']['passedArgs'][1])){
					$submenu_ativo = Set::extract('/Sitemap[friendly_url='.$this->params['originalArgs']['passedArgs'][1].']', $submenu);
				}
				
				//verifica se o submenu tem itens
				if($submenu_itens = $this->Sitemap->children(array('id' => $submenu_ativo[0]['Sitemap']['id'], 'direct' => true, 'fields' => array('id', 'label', 'friendly_url')))){
					if(isset($this->params['originalArgs']['passedArgs'][2])){
						$subsubmenu_ativo = Set::extract('/Sitemap[friendly_url='.$this->params['originalArgs']['passedArgs'][2].']', $submenu_itens);
					}
					
					//Faz os subitens serem incluidos no índice submenu do submenu ativo
					foreach($submenu AS $key=>$value){
						if($value['Sitemap']['id'] == $submenu_ativo[0]['Sitemap']['id']){
							$submenu[$key]['submenu'] = $submenu_itens;
							break;
						}
					}
				}
			}
		}
				
		
		//ULIMAS NOTICIAS
		$calendarios = $this->Calendario->find('all', array(
			'order' => 'Calendario.data asc',
			'fields' => array(
				'Calendario.id',
				'Calendario.data',
				'Calendario.hora',
				'Calendario.titulo', 
				'Calendario.descricao',
				'Calendario.descricao_preview',
				'Calendario.dia_semana'
			),
		));


		//breadcrumbs
		$breadcrumbs = array();
		if(empty($this->params['sitemap'])){
			
		}else if($this->params['controller'] == 'home'){
			//Descomentar a linha abaixo para incluir a home no breadcrumb
			//$breadcrumbs['Home'] = '/';
		}else{
			$current_path = '';
			foreach($this->Sitemap->getpath($this->params['sitemap']['Sitemap']['id'], array('friendly_url', 'label'), true) AS $bread){
				$current_path .= '/'.$bread['Sitemap']['friendly_url'];
				$breadcrumbs[$bread['Sitemap']['label']] = $current_path;
			}
		}
		
		$this->set(compact('breadcrumbs', 'menu', 'menu_ativo', 'submenu', 'submenu_ativo','subsubmenu_ativo','calendarios'));
	}

	function _setLanguage() {
		$default_language = Configure::read('Config.language');
		
		if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
			$this->Session->write('Config.language', $this->Cookie->read('lang'));
		}
		else if (!empty($default_language) && $default_language != $this->Session->read('Config.language')) {
			$this->Session->write('Config.language', $default_language);
			$this->Cookie->write('lang', $default_language, false, '20 days');
		}
		Configure::write('Config.language', $this->Session->read('Config.language'));

		$current_language = Configure::read('Config.language');
		$this->set('current_language', $current_language);
	}
}