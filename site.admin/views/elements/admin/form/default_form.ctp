<?php
if(!isset($setup['topLinkLeft']))$setup['topLinkLeft'] = array();
$setup['topLinkLeft'][$this->Html->image('icons/fugue/navigation-180.png').' Voltar'] = array('url' => $this->params['named'] + array('action' => 'index'), 'htmlAttributes' => array('escape' => false));
$this->set('setup', $setup);
?>
<h1><?php echo $form_title;?></h1>
<p>Por favor, preencha o formulário a seguir. Campos obrigatórios marcados com <em>*</em></p>
<?php 
echo $this->Form->create($model,array('url' => $this->params['named'], 'class'=>'form inline-medium-label',) + (!empty($setup['formParams']) ? $setup['formParams'] : array()));
?>
<fieldset>
	<?php
	echo $this->Form->input('id');
	if(!empty($setup['form'])){
		$i = 0;
		foreach($setup['form'] AS $key=>$params){					
			if(!isset($params['type']) || $params['type'] != 'hidden')$i++;

			if(!array_key_exists('div', $params))$params['div'] = 'input';
			if($i % 2 == 0)$params['div'] .= ' even';

			if(!empty($params['type']) && $params['type'] == 'checkbox' && !isset($this->data[$model][$key]) && isset($params['default']) && isset($params['value']) && $params['default'] == $params['value']){
				$params['checked'] = true;
			}
			
			if(!empty($params['type']) && $params['type'] == 'file'){
				if(!isset($params['after']))$params['after'] = '';
				
				if(strstr($key, '.') && !empty($dados_originais)){
					$value = Set::Extract($key, $dados_originais);
				}else if (!empty($dados_originais[$model][$key])){
					$value = $dados_originais[$model][$key];
				}else{
					$value = null;
				}
				
				if(isset($params['show_remove']) && !empty($value)){
					$params['after'] .= ' '.$this->Form->input($key.'.remove', array('error' => false, 'label' => (is_string($params['show_remove']) ? $params['show_remove'] : 'Remover arquivo atual - ('.basename($value).')'), 'div' => 'file_remove', 'value' => '', 'type' => 'checkbox'));
				}
				
				if(isset($params['show_preview']) && !empty($value)){
					$params['after'] .= ' '.$this->Html->link('Preview', (
						!empty($params['show_preview_url']) ? 
							$params['show_preview_url'].'?src='.rawurlencode($value).'&size='.rawurlencode($params['show_preview'])
							:
							array('controller' => 'thumbs', '?' => array('src' => $value, 'size' => $params['show_preview']))
						),
						array('class' => 'fancy')
					);
				}
			}

			echo $this->Form->input($key,$params);
			
		}
	}
?>
</fieldset>
<?php
if(empty($no_form_submit)) echo $this->Form->submit($form_submit_label,array('class' => 'big-button', 'div'=>false)).' ou '.$html->link(__('Cancelar', true), $this->params['named'] + array('action' => 'index'), array('class' => 'button small red'));
if(empty($no_form_end))echo $this->Form->end();
