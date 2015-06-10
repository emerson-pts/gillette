<?php
if(!empty($setup['listActions'])){
	foreach($setup['listActions'] AS $label=>$action){
		if(empty($action['url']['params'])){
			$action['url']['params'] = '';
		}else if(is_array($action['url']['params'])){
			foreach($action['url']['params'] AS $key=>$value){
				$action['url']['params'][$key] = current(Set::extract($value, $r));
			}
			$action['url']['params'] = implode('/', $action['url']['params']);
		}else{
			$action['url']['params'] = preg_replace('/\{(.*?)\}/e', 'current(Set::extract("\1", $r))', $action['url']['params']);
		}
		
		if(!empty($action['params']['class'])){
			$action['params']['class'] .= ' with-tip';
		}else{
			$action['params']['class'] = ' with-tip';
		}
		
		if(preg_match('/\.(png|gif|jpe?g)$/', $label)){
			if(empty($action['params']['title']))$action['params']['title'] = ucfirst(basename($label));
			$label = $this->Html->image($label);
			$action['params']['escape'] = false;
		}else if(empty($action['params']['title'])){
			$action['params']['title'] = $label;
		}
		
		if (empty($acl) || !method_exists($acl, 'check') || $acl->check('controllers/'.(!is_array($action['url']) ? $action['url'] : (isset($action['url']['controller']) ? $action['url']['controller'] : $this->params['controller']).(isset($action['url']['action']) ? '/'.$action['url']['action'] : '')))){
			if(empty($tag)){
				$tag = '%s ';
			}
			printf($tag, $this->Html->link($label, (!is_array($action['url']) ? $action['url'] : '/'.(isset($action['url']['controller']) ? $action['url']['controller'] : $this->params['controller']).(isset($action['url']['action']) ? '/'.$action['url']['action'] : '')).'/'.$action['url']['params'], $action['params']));
		}
	}

}
