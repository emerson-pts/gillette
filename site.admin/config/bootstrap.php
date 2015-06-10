<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array(/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

App::build(array(
    'models' =>  array(SITE_DIR . 'models' . DS),
    'behaviors' =>  array(SITE_DIR . 'models/behaviors' . DS),
    'vendors' =>  array(SITE_DIR . 'vendors' . DS),
    'helpers' =>  array(SITE_DIR . 'views' . DS . 'helpers' . DS),
));

Inflector::rules('singular', array('irregular' => array('banners' => 'banner',), 'rules' => array('/^(.*)coes$/i' => '\1cao', '/^(.*)ses$/i' => '\1s', '/^(.*)ns$/i' => '\1m', '/^(.*)res/i' => '\1r', '/^(.*)ves/i' => '\1ve', ),));
Inflector::rules('plural', array('irregular' => array('banner' => 'banners',), 'rules' => array('/^(.*)cao$/i' => '\1coes', '/^(.*)s$/i' => '\1ses', '/^(.*)m$/i' => '\1ns', '/^(.*)r$/i' => '\1res', '/^(.*)ve$/i' => '\1ves' ),));

Configure::write('Config.language', 'pt_br');

//Nome humanizado dos controllers
Configure::write('controllerTraduzido', array(
	'Acl'				=> 'Permissões	',
	'Usuarios'	 		=> 'Usuários',
	'Sitemaps'	 		=> 'Mapa do Site',
	'Noticias'	 		=> 'Notícias',
	'Paginas'	 		=> 'Páginas',
	'GaleriaArquivos'	=> 'Arquivos da Galeria',
	'Configurations'	=> 'Configurações',
	'Logs'				=> 'Logs - Registro de alterações',
	'Pages'				=> 'Páginas Estáticas',
	'FormaPagamentos'	=> 'Formas de Pagamento',
	'Calendarios'		=> 'Calendários',
	'JogosChaves'		=> 'Chaves',
	
	'controllers'		=>'Sistema',

	'index'	=> 'Listar',
	'add'	=> 'Novo',
	'edit'	=> 'Editar',
	'edit_parcial'	=> 'Edição parcial',
	'delete'=> 'Apagar',
	'del'	=> 'Apagar',
	'view'	=> 'Visualizar',
	'preview' => 'Pré-visualizar',
	'display'	=> 'Exibir',
	'movedown'	=> 'Mover para baixo',
	'moveup'	=> 'Mover para cima',
	'update_parent'	=> 'Alterar pai',
	
	'build_acl'			=> 'Recriar lista de permissões disponíveis',
	'set_permission'	=> 'Alterar permissão',
	
	'AjaxValidators'	=> 'Validação durante preenchimento',
	'change_pass' => 'Alterar senha',
));

//Monta menu
//	* no início do submenu é a ação padrão do menu. Para o menu funcionar, ao menos um submeno deve ter essa opcao
Configure::write('Admin.menu', array(
/*	'Home'	=> array(
		'Dashboard' => '/dashboard/index',
	),
	*/
	'Notícias' => array(	
		'Notícias' 	=> 	'/noticias/index',
			'/noticias/add',
			'/noticias/edit',
		/*'Programação' 	=> 	'/calendarios/index',
			'/calendarios/add',
			'/calendarios/edit',*/
	),

	'Conteúdo' => array(
		//Crie itens com índice numérico para linkar o menu ao acessar o endereço, sem exibir o item no menu
		'Páginas' 			=> '/paginas/index',
			'/paginas/add',
			'/paginas/edit', 
		'Galerias de fotos' 	=> '/galerias/index',
			'/galerias/add',
			'/galerias/edit',
			'/galeria_arquivos/index', 
			'/galeria_arquivos/add', 
			'/galeria_arquivos/edit',
		'Vitrines' 			=> '/vitrines/index',
			'/vitrines/add',
			'/vitrines/edit',
		/*'História' 			=> '/timelines/index',
			'/timelines/add',
			'/timelines/edit',*/
		/*'Boletim' 			=> '/boletins/index',
			'/boletins/add',
			'/boletins/edit',*/
		'Mapa do Site'		=> '/sitemaps/index',
			'/sitemaps/add',
			'/sitemaps/edit',
	),

	'Jogos'	=> array(
		/*'Chaves' =>
			'/jogos/index',
			'/jogos/add',
			'/jogos/edit',*/
		'Jogadores'		=> '/jogadores/index',
			'/jogadores/add',
			'/jogadores/edit',
	),
	
	'Setup' => array(
		'/acl/index',
						
		'Configurações'		=> '/configurations/index',
			'/configurations/add',
			'/configurations/edit',

		'Usuários'			=> '/usuarios/index',
			'/usuarios/add',
			'/usuarios/edit',
		'Grupos de Usuários'	=> '/grupos/index',
			'/grupos/add',
			'/grupos/edit',
		'Cache'	=> '/caches/index',
	),
));

/* 
Descomente para site multi-idiomas
//LANGUAGE - start
Configure::write('Config.languages_options', $languages_options = array(
	'www' => array(
		'subdomain'	=> 'www',
		'title' 	=> 'Português', 
		'language'	=> 'por',
		'database'	=> '%s',//usar database padrão
	),
	
	'spa' => array(
		'subdomain'	=> 'spa',
		'title' 	=> 'Español', 
		'language'	=> 'spa',
		'database'	=> '%s_spa',
	),
));

$subdomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER["HTTP_HOST"], "."));
if(!isset($languages_options[$subdomain]))$subdomain = key($languages_options);

Configure::write('Config.language_options', $current_language = $languages_options[$subdomain]); 
//Configure::write('Config.language', $current_language['language']);
		
//LANGUAGE - end
*/
	
if(!strstr($_SERVER['SERVER_NAME'], 'localhost'))ini_set('session.cookie_domain', preg_replace('/^[^\.]+\./', '.', $_SERVER['HTTP_HOST']));
session_start();