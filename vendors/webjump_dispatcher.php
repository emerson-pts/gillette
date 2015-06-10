<?php
class WebjumpDispatcher extends Dispatcher{
	function cached($url) {
		if(!empty($this->params['originalArgs']['here'])){
			$here_original = $this->here;
			$this->here = $this->params['originalArgs']['here'];
		}
/*
		$return = parent::cached($url);
		return $return;
	}

	function cached($url) {
*/

		if (Configure::read('Cache.check') === true) {
			$path = $this->here;
			if ($this->here == '/') {
				$path = 'home';
			}
			$path = strtolower(Inflector::slug($path));

			$filename = CACHE . 'views' . DS . Configure::read('Config.language').'_'.$path . '.php';

			if (!file_exists($filename)) {
				$filename = CACHE . 'views' . DS . Configure::read('Config.language').'_'.$path . '_index.php';
			}

			if (file_exists($filename)) {
				if (!class_exists('View')) {
					App::import('View', 'View', false);
				}
				$controller = null;
				$view =& new View($controller);
				$return = $view->renderCache($filename, getMicrotime());
				if (!$return) {
					ClassRegistry::removeObject('view');
				}
				return $return;
			}
		}
		return false;
	}
}