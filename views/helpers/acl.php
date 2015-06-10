<?php 
class AclHelper extends AppHelper {      
	var $helpers = array("Session");  
	var $Acl;  
	var $User;
	var $Session;
   
	function __construct(){  
		parent::__construct();
		App::import('Component', 'Acl');
		$this->Acl = new AclComponent();

		App::import('Core', 'CakeSession'); 
		$this->Session = new CakeSession(); 
		if(!$this->Session->started())$this->Session->start();
		$this->User = $this->Session->read('Auth.Usuario');	

		/*
		App::import('Component', 'Auth');
		$this->Auth = new AuthComponent();
		$this->Auth->Session = $this->Session;

		$this->User = $this->Auth->User();
		*/
	}   
   
	function check($url, $action='*', $write_cache = true){
		$aco = preg_replace('/\/([^\/]+)\//e', '"/".Inflector::camelize("\1")."/"', $url);
		if(empty($this->User)) return false;  
		
 		$cacheKey = 'AclHelper_check.'.$this->User['id'].'.'.str_replace('/','_',$aco);
		if ($return = Cache::read($cacheKey)) {
			return current($return);
		}else{	
			$acoPath = $this->Acl->Aco->node($aco);
			if(empty($acoPath)){
				$return = false;
			}else{
				$return = $this->Acl->check(array('model'=> 'Usuario', 'foreign_key' => $this->User['id']), $aco, $action); 
			}
			if($write_cache == true){
				Cache::write($cacheKey, array($return));
			}
			return $return;
		}  
	}
} 