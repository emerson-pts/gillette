<!-- Status bar -->
<div id="status-bar"><div class="container_12">

	<ul id="status-infos">
		<li class="spaced"><?php echo __('Conectado como');?>: <strong><?php echo $this->Html->link($this->Session->read('Auth.Usuario.nome'), array('controller' => 'usuarios', 'action' => 'change_pass'), array('class' => 'with-tip', 'title' => 'Alterar senha',)); ?></strong></li>
		<li><?php echo $this->Html->link('<span class="smaller">'.__('Sair', true).'</span>', array('controller'=>'usuarios','action'=>'logout'), array('escape' => false, 'title' => 'Sair', 'class' => 'with-tip button red',));?>
	</ul>
	
	<!-- v1.5: you can now add class red to the breadcrumb -->
	<ul id="breadcrumb">
		<li><?php 
			//Adiciona breadcrumbs setados no controller
			foreach($breadcrumbs AS $key=>$value)$this->Html->addCrumb($value, $key);
			//Exibe breadcrumbs
			echo $this->Html->getCrumbs('</li><li>');
		?></li>
	</ul>
</div></div>
<!-- End status bar -->
