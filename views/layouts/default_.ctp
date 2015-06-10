<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php
	echo $this->Html->charset();
	?><title><?php echo __(Configure::read('site.title')) . (empty($title_for_layout) ? '' : ': '.$title_for_layout); ?></title><?php

	echo $this->Html->meta('icon');

	echo $this->Html->css(array('reset', 'style', 'messages', 'prettyPhoto','jquery.tooltip','screen'));
	echo $this->Html->script(array('jquery-1.7.1.min', 'jquery.tooltip', 'jquery.scrollTo-1.4.2-min','jquery.prettyPhoto', 'jquery.maskedinput-1.1.2.pack', 'jquery.ajaxValidate','chili-1.7.pack','jquery.bgiframe'));

	if(Configure::read('debug') >= 2)echo $this->Html->css(array('sql_debug'));

	//Png fix
	?>
	<!--[if lt IE 7]>
		<?php echo $this->Html->script(array('unitpngfix/unitpngfix'));?>
	<![endif]-->
	<?php
	echo $scripts_for_layout;


	echo $this->Html->meta('keywords', (!empty($meta['keyword']) ? $meta['keyword'] : Configure::read('meta.keyword')), array('type' => 'keywords'), false);
	echo $this->Html->meta('description', (!empty($meta['description']) ? $meta['description'] : Configure::read('meta.description')), array('type' => 'description'), false);

	?><script type="text/javascript">
	jQuery(document).ready(function() {
		/*jQuery("a[rel^='prettyPhoto'], a.prettyphoto, a.lightbox, a.prettyPhoto").prettyPhoto({show_title: false});
		jQuery('a[href^=http]').each(function(){
			if(this.href.indexOf(location.hostname) == -1)jQuery(this).attr('target', '_blank');
		});*/
		
		jQuery('#_tooltip a').tooltip({track: true,delay: 0,showURL: false,showBody: " - ",fade: 250});
		<?php if($this->params['controller'] != 'home'):?>
			scrollTime =  Math.abs(($('.menu_bg').offset().top - $(window).scrollTop()) * 2.4);
			if(scrollTime < 400){scrollTime = 400;}else if(scrollTime > 1200){scrollTime = 1200;}
			$.scrollTo($('.menu_bg'), scrollTime);
		<?php endif;?>
	});
	</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-10097433-42']);
  _gaq.push(['_setDomainName', 'brasilopen.com.br']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script></head>
<body>
	<div class="topo"></div><?php
	echo $this->Element('menu');

	?><div class="bg">
		<div class="wrap">
			<div class="titulo_principal"></div><?php

			?><cake:nocache><?php echo $this->Session->flash(); ?></cake:nocache><?php

			echo $content_for_layout;


				?><div class="adicionais">
					<div class="informativos"><?php
						echo $this->Form->create('FormInformativo', array('url' => array('controller' => 'form_informativos', 'action' => 'index',)));

					    echo $this->Form->input("nome",array(
					    	"maxlength" => "100",
					    	"size" => "60",
					    	'label' => 'Nome',
					    	'class' => 'text'
					    ));
					    echo $this->Form->input("email",array(
					    	"size" => "60",
					    	"maxlength"=>"80",
					    	'label' => 'E-mail',
					    ));
					    echo $this->Form->end(array(
							'label' => 'OK',
							'class' => 'submit',
							'div'=>false,
						));

						
					
					?></div><?php

					//MIDIAS SOCIAIS
					$midias_sociais = Configure::read("midias_sociais");
					if(!empty($midias_sociais)){
						?><div class="midias-sociais"><?php
							foreach($midias_sociais as $key=>$midia){
								if(!empty($midia)){
									
									echo $this->Html->link(
										$this->Html->image($key.'.jpg',array("alt" => $key)),
										$midia,
										array('target'=>'_blank','escape'=>false)
									);
								}
							}
						?></div><?php
					}
					?><br class="clear" />
				</div>
			</div>
		<div class="footer">
			<?php echo $this->Html->image('footer/footer.png',array('usemap'=>'#footer'));?>
			<MAP NAME="footer">
				<AREA HREF="http://www.gillette.com/pt/BR/home.aspx" ALT="Gillette" TITLE="Gillette" SHAPE=RECT COORDS="47,77,175,124">
				<AREA HREF="http://www.prefeitura.sp.gov.br/cidade/secretarias/esportes/" SHAPE=RECT COORDS="248,79,345,118">
				<AREA HREF="http://www.capital.sp.gov.br" SHAPE=RECT COORDS="418,79,516,104">
				<AREA HREF="http://www.selt.sp.gov.br/" SHAPE=RECT COORDS="590,78,692,114">
				<AREA HREF="http://www.atpworldtour.com/" SHAPE=RECT TITLE="ATP WORLD TOUR" COORDS="766,78,799,116">
				<AREA HREF="http://cbtenis.uol.com.br/" SHAPE=RECT TITLE="CBTENIS" COORDS="834,79,868,115">
				<AREA HREF="http://www.kochtavares.com.br/koch/" SHAPE=RECT TITLE="KOCK TAVARES" COORDS="922,79,990,117">
				<AREA HREF="http://www.vw.com.br/pt.html" SHAPE=RECT TITLE="volkswagen" COORDS="44,183,87,225">
				<AREA HREF="http://www.bb.com.br/portalbb/page67,116,2068,1,1,1,1.bb?codigoMenu=9087&codigoNoticia=9080&codigoRet=210&bread=2" SHAPE=RECT COORDS="142,192,202,216">
				<AREA HREF="https://www.americanexpress.com/br" TITLE="American Express" SHAPE=RECT COORDS="205,189,236,220">
				<AREA HREF="http://corona.com/home/index.jsp" TITLE="Corona Extra" SHAPE=RECT COORDS="291,185,347,223">
				<AREA HREF="http://www.asics.com.br/" TITLE="Asics" SHAPE=RECT COORDS="403,194,479,215">
				<AREA HREF="http://www.nextel.com.br/" TITLE="NEXTEL" SHAPE=RECT COORDS="535,194,610,213">
				<AREA HREF="http://www.correios.com.br/" SHAPE=RECT COORDS="666,193,741,213">
				<AREA HREF="http://www.petrobras.com.br/pt/" SHAPE=RECT COORDS="797,184,860,222">
				<AREA HREF="http://www.brasil.gov.br/" SHAPE=RECT COORDS="915,191,991,213">
				<AREA HREF="http://www.omint.com.br/" SHAPE=RECT COORDS="57,290,92,328">
				<AREA HREF="http://www.tivolihotels.com/" SHAPE=RECT COORDS="175,292,225,323">
				<AREA HREF="http://www.babolat.com.br/" SHAPE=RECT COORDS="271,303,339,316">
				<AREA HREF="http://www.galetos.com.br/" SHAPE=RECT COORDS="383,300,460,320">
				<AREA HREF="http://www.casillerodeldiablo.com/" SHAPE=RECT COORDS="530,294,566,319">
				<AREA HREF="http://www.artefactobc.com.br/" SHAPE=RECT COORDS="595,292,625,321">
				<AREA HREF="http://www.bykamy.com/" SHAPE=RECT COORDS="654,299,705,315">
				<AREA HREF="http://www.jasminealimentos.com/" SHAPE=RECT COORDS="734,298,785,315">
				<AREA HREF="http://www.selmi.com.br/" SHAPE=RECT COORDS="813,296,865,318">
				<AREA HREF="http://www.tenispaulista.com.br/" SHAPE=RECT COORDS="894,292,930,321">
				<AREA HREF="http://www.circulomilitar.com.br/" SHAPE=RECT COORDS="959,291,990,323">
			</MAP>

		
		</div>
		<div class="desenvolvimento"><a href="http://www.webjump.com.br" target="_blank"><?php echo $this->Html->image('desenvolvimento.png');?></a></div>
	</div><?php

	echo $this->element('sql_dump'); echo $this->Js->writeBuffer();

?></body>
</html>