<?php
//Se é o envio individual
if(empty($this->params['pass'][0]) || $this->params['pass'][0] != 'multiple'){
	echo $this->Element('admin/add');
	return;
}else{
	echo $this->Element('admin/multiple_upload');
	return;
}
