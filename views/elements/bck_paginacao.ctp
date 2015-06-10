<?php 
//$paginator->options(array('url' => $this->passedArgs));
$this->Paginator->options(array('url' => $this->params['originalArgs']['passedArgs']));
	echo '<div class="paging paginacao_produtos"><div class="alinhar">'.
		$paginator->prev(''.__('anterior', true), array(), null, array('class'=>'disabled')).
		' '.$paginator->numbers(array('separator' => false)).
		' '.$paginator->next(__('próxima', true).'', array(), null, array('class' => 'disabled')).'
	</div></div>';
?>