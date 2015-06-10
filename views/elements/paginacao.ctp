<?php 
//$paginator->options(array('url' => $this->passedArgs));
// echo '<pre>';print_r($this->params);echo '</pre>';
$this->Paginator->options(array('url' => $this->params['originalArgs']['passedArgs'][0]));
	echo '<div class="paging paginacao_produtos"><div class="alinhar">'.
		$paginator->prev(''.__('anterior', true), array(), null, array('class'=>'disabled')).
		' '.$paginator->numbers(array('separator' => false)).
		' '.$paginator->next(__('próxima', true).'', array(), null, array('class' => 'disabled')).'
	</div></div>';
?>