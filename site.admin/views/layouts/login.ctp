<!doctype html>
<!--[if lt IE 8 ]><html lang="en" class="no-js ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie"><![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
<head>
	<?php echo $this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>
		<?php echo Configure::read('site.title') . (empty($title_for_layout) ? '' : ': '.$title_for_layout); ?>
	</title>

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta(array('robots' => 'noindex,nofollow'));
	
		//Global stylesheets
		echo $this->Html->css(array('reset','webjump.default', 'common','form','standard', 'special-pages', 'webjump.template', 'sql_debug'));
	
		//Modernizr for support detection, all javascript libs are moved right above </body> for better performance 
		echo $this->Html->script(array('libs/modernizr.custom.min.js', 'libs/jquery-1.6.3.min.js', ));
	?>	
</head>

<!-- the 'special-page' class is only an identifier for scripts -->
<body class="special-page login-bg dark">
	<section id="login-block">
		<?php 
		if(file_exists(WWW_ROOT.'img/header_logo.png')){
			echo $this->Html->tag('div', $this->Html->image('header_logo.png'), array('class' => 'header-logo'));
		}
		?>
		<div class="block-border"><div class="block-content">
			<!--
			IE7 compatibility: if you want to remove the <h1>, 
			add style="zoom:1" to the above .block-content div
			-->
			<h1>Login</h1>
			<div class="block-header"><?php echo Configure::read('site.title');?></div>
			<?php
			//<p class="message error no-margin">Error message</p>
				echo $this->Session->flash('auth');
				echo $content_for_layout;
			?>
		</div></div>

		<?php 
		//Verifica se tem mensagem a ser exibida
		if($this->Session->read('Message.flash')):
		?>	
			<br />
			<div class="block-border"><div class="block-content no-title dark-bg">
				<?php echo $this->Session->flash();?>
			</div></div>
		<?php endif; ?>
		<div class="webjump_logo">
			<?php echo $this->Html->link($this->Html->image('webjump.png', array('alt' => 'Desenvolvido por Webjump')), 'http://www.webjump.com.br', array('target' => '_blank', 'escape' => false));?>
		</div>
	</section>
	<footer>
<?php 
/*
		<div class="webjump_logo">
			<?php echo $this->Html->link($this->Html->image('webjump.png', array('alt' => 'Desenvolvido por Webjump')), 'http://www.webjump.com.br', array('target' => '_blank', 'escape' => false));?>
		</div>
*/?>
	</footer>

	<!--
	
	Updated as v1.5:
	Libs are moved here to improve performance
	
	-->
	<?php
		// Generic libs 
		echo $this->Html->script(array('old-browsers.js', 'common', 'standard', 'jquery.form'));
	?>
	<!--[if lte IE 8]><?php echo $this->Html->script('standard.ie.js');?><![endif]-->
	<?php
		echo $this->Html->script('jquery.tip.js');
	?>
</body>
</html>