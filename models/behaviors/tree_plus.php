<?php
App::import('Behavior', 'Tree');
class TreePlusBehavior extends TreeBehavior {    
    function getfullpath(&$Model, $id, $separator = '/', $label = null) {
		
		$cache_key = Configure::read('Config.language_options.language').'_'.get_class($this).'_'.__FUNCTION__.'_'.md5(serialize(func_get_args()));
		if(false !== ($return = Cache::read($cache_key)))return $return;
		
		if(is_numeric($id))
            $cats = $Model->getpath($id);
        else if(is_array($id))
            $cats = $id;
        else
            return false;
        
        $path = array();
        foreach ($cats as $cat) {
            array_push($path, $cat[$Model->name][(empty($label) ? $Model->displayField : $label)]);
        }
		
		$return = implode($separator, $path);
		
		Cache::write($cache_key, $return);
        return $return;
    }

	function findPath(&$Model, $paths, $fieldName) {
		$root = $Model->find('threaded');
		return $this->findPathHelper($Model, $root, $paths, $fieldName);
	}

	function findPathHelper(&$Model, $node, $paths, $fieldName) {
		// search for paths from the beginning of the list
		$path = array_shift($paths);
		$newNode = false;
		// looking for the current path in the specified field
		foreach ($node as $nodeNode) {
			if ($nodeNode[$Model->name][$fieldName] == $path) {
				$newNode = $nodeNode;
				break;
			}
		}
		if (empty($paths) || $newNode === false) {
			// done processing the paths, or cannot find a matching node
			return $newNode;
		} else {
			// not done, find based on the found node's children
			return $this->findPathHelper($Model, $newNode['children'], $paths, $fieldName);
		}
	}

}