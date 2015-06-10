<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php
	echo $this->Html->charset();
	?><title><?php echo __(Configure::read('site.title')) . (empty($title_for_layout) ? '' : ': '.$title_for_layout); ?></title><?php

	echo $this->Html->meta('icon');

	echo $this->Html->css(array('reset', 'style', 'messages', 'prettyPhoto','jquery.tooltip','screen', 'foundation.min', 'app', ));
	echo $this->Html->script(array('jquery-1.7.1.min', 'jquery.tooltip', 'jquery.scrollTo-1.4.2-min','jquery.prettyPhoto', 'jquery.maskedinput-1.1.2.pack', 'jquery.ajaxValidate','chili-1.7.pack','jquery.bgiframe','jquery.bxSlider.min', 'modernizr.foundation', 'foundation.min.js', 'app'));

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
	  _gaq.push(['_setAccount', 'UA-36629830-3']);
	  _gaq.push(['_setDomainName', 'gillettefederertour.com.br']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
</head>
<body>
	<a href="<?php echo $this->Html->url('/'); ?>" >
		<div class="topo">
		<!-- Idiomas
		<?php echo $this->Html->link($this->Html->image('br.png',array('style'=>'margin: 10px 0 0 1100px;')), "http://www.brasilopen.com.br/site/", array('escape' => false));?>
		
		<?php echo $this->Html->link($this->Html->image('es.png',array('style'=>'margin: 0 0 0 5px;')), "http://www.brasilopen.com.br/espanhol/", array('escape' => false));?>
		-->
		</div>
	</a>
	<?php
		echo $this->Element('menu');
	?>
	<div class="bg">
		<cake:nocache><?php echo $this->Session->flash(); ?></cake:nocache>
		<?php
			echo $content_for_layout;
	    ?>
	<div class="wrap footer">
	<!--
	<?php
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
		-->
		<div class="footer">
		<!--
			<h3>
				Patrocinadores
			</h3>
		-->
			<?php echo $this->Html->image('footer/footer.png',array('usemap'=>'#Image-Maps_7201212061521232'));?>
			<map id="_Image-Maps_7201212061521232" name="Image-Maps_7201212061521232">
				<area shape="rect" coords="410,45,584,100" href="http://www.gillette.com/pt/br/home.aspx" alt="gillette" title="gillette"    />
				<area shape="rect" coords="4,171,64,226" href="http://www.omint.com.br/dnnomint/home/aomint.aspx" alt="omint" title="omint"    />
				<area shape="rect" coords="72,174,163,229" href="http://www.rolex.com/" alt="rolex" title="rolex"    />
				<area shape="rect" coords="176,169,283,224" href="http://www.baruel.com.br/tenys-pe-baruel/" alt="tenys pé" title="tenys pé"    />
				<area shape="rect" coords="763,172,879,227" href="http://www.image-maps.com/" alt="http://www.correios.com.br/" title="http://www.correios.com.br/"    />
				<area shape="rect" coords="890,171,995,226" href="http://www.brasil.gov.br/" alt="governo brasil" title="governo brasil"    />
				<area shape="rect" coords="0,278,120,333" href="http://www.voeazul.com.br/?s_cid=lpgoogle_azul_tarifasinstitucional&gclid=ci7hgiqwwrmcfqednqod-cyacg" alt="azul" title="azul"    />
				<area shape="rect" coords="143,280,253,335" href="http://www.wilson.com/en-us/international/" alt="wilson" title="wilson"    />
				<area shape="rect" coords="281,278,391,333" href="http://www.marriott.com/hotels/travel/saobr-renaissance-sao-paulo-hotel/" alt="renaissance hotel" title="renaissance hotel"    />
				<area shape="rect" coords="427,280,510,335" href="http://www.adidas.com.br/" alt="adidas" title="adidas"    />
				<area shape="rect" coords="552,280,651,335" href="http://www.santacarolina.cl/" alt="santa carolina" title="santa carolina"    />
				<area shape="rect" coords="694,277,750,332" href="http://www.artefactobc.com.br/" alt="artefactobc" title="artefactobc"    />
				<area shape="rect" coords="800,287,887,322" href="http://www.tapetes.com/" alt="bykamy" title="bykamy"    />
				<area shape="rect" coords="574,381,659,437" href="http://www.kochtavares.com.br/koch/" alt="koch tavares" title="koch tavares"    />
				<area shape="rect" coords="4,377,65,433" href="http://www.band.uol.com.br/" alt="bandeirantes" title="bandeirantes"    />
				<area shape="rect" coords="91,381,160,432" href="http://www.bandsports.uol.com.br/" alt="band sports" title="band sports"    />
				<area shape="rect" coords="180,384,264,429" href="http://sportv.globo.com/site/" alt="sportv" title="sportv"    />
				<area shape="rect" coords="285,381,349,426" href="www.istoe.com.br/capa" alt="istoé" title="istoé"    />
				<area shape="rect" coords="370,383,457,428" href="http://www.estadao.com.br/" alt="estadão" title="estadão"    />
				<area shape="rect" coords="473,379,545,424" href="http://tunein.com/radio/r%c3%a1dio-eldorado-fm-1073-s7037/" alt="eldorado fm 107.3" title="eldorado fm 107.3"    />
				<area shape="rect" coords="687,374,820,435" href="http://www.capital.sp.gov.br/portalpmsp/homec.jsp" alt="prefeitura são paulo" title="prefeitura são paulo"    />
				<area shape="rect" coords="878,371,995,432" href="http://www.saopaulo.sp.gov.br/" alt="governo são paulo" title="governo são paulo"    />
				<area shape="rect" coords="943,278,992,334" href="http://www.gatorade.com.br/#inicial" alt="gatorade" title="gatorade"    />
				<area shape="rect" coords="290,170,350,226" href="http://www.bmw.com.br/br/pt/index.html" alt="bmw" title="bmw"    />
				<area shape="rect" coords="690,172,754,227" href="http://www2.americanexpress.com.br/conteudo/cartoespessoais.aspx" alt="American Express" title="American Express"    />
				<area shape="rect" coords="581,168,674,223" href="http://www.gillettevenus.com/en_US/index.jsp" alt="Gillette Venus" title="Gillette Venus"    />
				<area shape="rect" coords="483,172,567,227" href="http://www.headandshoulders.com.br/pt-BR/index.jspx" alt="Head&Shoulders" title="Head&Shoulders"    />
				<area shape="rect" coords="370,169,464,224" href="http://jhsf.com.br/" alt="JHSF" title="JHSF"    />
				<area shape="rect" coords="998,444,1000,446" href="http://www.image-maps.com/index.php?aff=mapped_users_7201212061521232" alt="Image Map" title="Image Map" />
			</map>
			<!-- Image map text links - Start - If you do not wish to have text links under your image map, you can move or delete this DIV -->
			<div style="text-align:center; font-size:12px; font-family:verdana; margin-left:auto; margin-right:auto; width:1000px;">
				<a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.gillette.com/pt/br/home.aspx" title="gillette"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.omint.com.br/dnnomint/home/aomint.aspx" title="omint"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.rolex.com/" title="rolex"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.baruel.com.br/tenys-pe-baruel/" title="tenys pé"</a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.image-maps.com/" title="http://www.correios.com.br/"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.brasil.gov.br/" title="governo brasil"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.voeazul.com.br/?s_cid=lpgoogle_azul_tarifasinstitucional&gclid=ci7hgiqwwrmcfqednqod-cyacg" title="azul"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.wilson.com/en-us/international/" title="wilson"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.marriott.com/hotels/travel/saobr-renaissance-sao-paulo-hotel/" title="renaissance hotel"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.adidas.com.br/" title="adidas"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.santacarolina.cl/" title="santa carolina"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.artefactobc.com.br/" title="artefactobc"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.tapetes.com/" title="bykamy"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.kochtavares.com.br/koch/" title="koch tavares"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.band.uol.com.br/" title="bandeirantes"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.bandsports.uol.com.br/" title="band sports"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://sportv.globo.com/site/" title="sportv"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="www.istoe.com.br/capa" title="istoé"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.estadao.com.br/" title="estadão"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://tunein.com/radio/r%c3%a1dio-eldorado-fm-1073-s7037/" title="eldorado fm 107.3"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.capital.sp.gov.br/portalpmsp/homec.jsp" title="prefeitura são paulo"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.saopaulo.sp.gov.br/" title="governo são paulo"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.gatorade.com.br/#inicial" title="gatorade"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.bmw.com.br/br/pt/index.html" title="bmw"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www2.americanexpress.com.br/conteudo/cartoespessoais.aspx" title="American Express"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.gillettevenus.com/en_US/index.jsp" title="Gillette Venus"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://www.headandshoulders.com.br/pt-BR/index.jspx" title="Head&Shoulders"></a>
			 <a style="text-decoration:none; color:black; font-size:12px; font-family:verdana;" href="http://jhsf.com.br/" title="JHSF"></a>
			 			</div>
			<!-- Image map text links - End - -->	
		
		</div>
		<div class="desenvolvimento" style="margin-top:80px;"><a href="http://www.webjump.com.br" target="_blank"><?php echo $this->Html->image('desenvolvimento.png');?></a></div>
	</div>
</div>
<?php

	echo $this->element('sql_dump'); echo $this->Js->writeBuffer();

?></body>
</html>