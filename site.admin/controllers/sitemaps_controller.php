<?php
class SitemapsController extends AppController {

	var $name = 'Sitemaps';
	var $components = array('TreeAdmin');

	function movedown($id, $delta = 1) {
		$this->TreeAdmin->movedown($id, $delta);
	}
	
	function moveup($id, $delta = 1) {
		$this->TreeAdmin->moveup($id, $delta);
	}
	
	function update_parent(){
		$this->TreeAdmin->update_parent($this->data);
	}
		
	function index(){
		$this->_admin_index();
	}

	function add($parent_id = null){
		//Se definiu o pai
		if(!empty($parent_id) && empty($this->data)){
			$this->data['Sitemap']['parent_id'] = $parent_id;
		}
		$this->_admin_add();
		
	}

	function edit($id = null){
 		$this->_admin_edit($id);
	}

	function delete($id = null){
		$this->_admin_delete($id);
	}

}