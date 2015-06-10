<?php
if(!isset($setup['topLinkLeft']))$setup['topLinkLeft'] = array();
$setup['topLinkLeft'][$this->Html->image('icons/fugue/navigation-180.png').' Voltar'] = array('url' => array('controller' => 'galerias', 'action' => 'index',) + (empty($results[0]) ? array() : array('filter[GaleriaArquivo.galeria_id]' => $results[0]['Galeria']['parent_id'])), 'htmlAttributes' => array('escape' => false));
$this->set('setup', $setup);

echo $this->Element('admin/sortable_index');
