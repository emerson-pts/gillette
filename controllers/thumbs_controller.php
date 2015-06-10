<?php 
class ThumbsController extends Controller{
	var $name = 'Thumbs';
	var $uses = null;
	var $layout = null;
	var $autoRender = false;

	function index(){
		App::import('Vendor','ResizeImg'); 
		$resize = ResizeImg::resize(@$_GET['src'], @$_GET['size'], @$_GET['crop']);
		
		//Se ocorreu algum erro
		if(!empty($resize['error'])){
			echo $resize['error'];
			exit;
		}
		
		//Caso contrário
		else{
			header('Location: '.$resize['url']);
			exit;
		}
	}
}