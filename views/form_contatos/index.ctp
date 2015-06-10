<div class="content_top"></div>
<div class="content_middle">
    <div class="linha-titulo-home"><?php
			echo $this->Html->tag('h2',(isset($menu_ativo[0]['Sitemap']['label']))?$menu_ativo[0]['Sitemap']['label']:'');
	       	echo $this->element('vendas');
	        echo $this->Html->tag('br','',array('class'=>'clear'));
	?></div>
	<cake:nocache><?php echo $this->Session->flash('form');?></cake:nocache>
	<div style="width:373px;display:block;float:right;clear:right;margin-top:40px">
		<h3>Complexo Desportivo Constâncio Vaz Guimarães</h3>
		Endereço: Rua Manoel da Nóbrega, nº 1361<br />
		Ibirapuera – São Paulo – SP<br />
		Telefone: 3887-3500<br />
		<br />
		Metrô Ana Rosa + Ônibus Jardim Maria Sampaio<br />
		<br />
		475R IDA TERM. PQ. D.PEDRO II<br />
		475R VOLTA JD. SÃO SAVERIO<br />
		477U IDA SHOP. IGUATEMI (CIRC<br />
		509J IDA TERM. PRINC. ISABEL<br />
		509J VOLTA JD. SELMA<br />
		5154 VOLTA TERM. STO. AMARO<br />
		5164 VOLTA V. STA. CATARINA<br />
		5175 VOLTA BALNEARIO SÃO FCO.<br />
		5178 VOLTA JD. MIRIAM<br />
		5185 VOLTA TERM. GUARAPIRANGA<br />
		5194 VOLTA JD. APURA<br />
		677A IDA METRÔ ANA ROSA<br />
		677A VOLTA VILA GILDA<br />
		775A IDA METRÔ VILA MARIANA<br />
		775A VOLTA JD. ADALGIZA<br />
		775C IDA METRÔ ANA ROSA<br />
		775C VOLTA JD. MARIA SAMPAIO<br />
		846M IDA SHOP. IBIRAPUERA<br />
		846M VOLTA JD. MARISA<br />
	</div>
	
	<div style="width:490px;display:block;float:left">
		<h5>Como chegar</h5>
		<?php echo Configure::read('contato.googleMap');?>
	</div>
		<br class="clear">
		<br class="clear">
		<h5>Fale conosco</h5>
		<?php
		echo $this->Form->create('FormContato', array('url' => (isset($this->params['originalArgs']['params']['url']['url']) ? '/'.$this->params['originalArgs']['params']['url']['url'] : null), 'class' => 'contato'));



			
			echo $this->Form->input("nome",array(
				"maxlength" => "100",
				'label' => 'Nome',
			));
			
			echo $this->Form->input("email",array(
				"maxlength" => "100",
				'div'=>array('class'=>'text right')
			));
			
			echo $this->Form->input("endereco",array(
				"maxlength" => "100",
				'label' => __('Endereço', true),
			));
			echo $this->Form->input("telefone",array(
				"maxlength"=>"20",
				'label' => __('Telefone', true),
				'div'=>array('class'=>'text right')
			));
		
						
			echo $this->Html->tag('br','');
		
		echo $this->Form->input("mensagem",array(
				"cols"=> "75",
				"rows"=> "7",
				'type' => 'textarea',
				'limit'	=> '255',
				'div'=>'clear',
			));

	    echo $this->Form->end(array(
			'label' => 'Enviar',
			'value' => 'Send',
			'div'=>false,
			'style'=>'width:892px;line-height:25px;display:block;background-color:#f90;color:#fff;font-weight:bold;font-size:14px;border:1px solid #ccc;margin-top:0px;padding:0px',
		));


	
	
?></div>
<div class="content_bottom"></div>