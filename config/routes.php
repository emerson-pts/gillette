<?php
/**
 * Routes Configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
//    Router::parseExtensions('rss');

    Router::connect('/', array('controller' => 'home', 'action' => 'index'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/paginas/index/*', array('controller' => 'paginas', 'action' => 'index'));
	Router::connect('/paginas/*', array('controller' => 'paginas', 'action' => 'index'));
	
	Router::connect('/template_cursos/', array('controller' => 'cursos', 'action' => 'index'));
	Router::connect('/template_cursos/:action', array('controller' => 'cursos', 'action' => 'view'));

	Router::connect('/template_programacao/*', array('controller' => 'calendarios', 'action' => 'index'));

	Router::connect('/form_contatos', array('controller' => 'form_contatos', 'action' => 'index'));
	Router::connect('/form_contatos/:action/*', array('controller' => 'form_contatos', 'action' => 'index'));

	Router::connect('/form_informativos', array('controller' => 'form_informativos', 'action' => 'index'));
	
	Router::connect('/form_inscricao_pilotos', array('controller' => 'form_inscricao_pilotos', 'action' => 'index'));
	Router::connect('/form_inscricao_pilotos/:action/*', array('controller' => 'form_inscricao_pilotos', 'action' => 'index'));
	
	
	Router::connect('/template_galerias/*', array('controller' => 'galerias', 'action' => 'index'));	
	Router::connect('/galeria/*', array('controller' => 'galerias', 'action' => 'detalhe'));
	
	Router::connect('/download/*', array('controller' => 'galerias', 'action' => 'download'));	

	Router::connect('/template_boletim/*', array('controller' => 'boletins', 'action' => 'index'));
	
	Router::connect('/noticias/*', array('controller' => 'noticias', 'action' => 'index', 'noticia'));
	Router::connect('/noticia/*', array('controller' => 'noticias', 'action' => 'noticia', 'noticia'));

	Router::connect('/template_timelines/*', array('controller' => 'timelines', 'action' => 'index'));
	Router::connect('/template_jogadores/*', array('controller' => 'jogadores', 'action' => 'index'));

	Router::connect('/grupos/*', array('controller' => 'chaves', 'action' => 'index'));

	Router::connect('/thumbs/*', array('controller' => 'thumbs', 'action' => 'index'));
	
	//Faz com que qualquer endereço seja direcionado para o sitemap para redirecionar a rota correta
	Router::connect('/*', array('controller' => 'sitemaps', 'action' => 'index'));

