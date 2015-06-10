<?php echo $this->set('title_for_layout', 'História'); ?>
<?php
echo $this->Html->css(array('jquery.timelinr.style_v',), $rel = null, array('inline' => false));
echo $this->Html->script(array('jquery.timelinr-0.9.4', 'jquery.sizes.min',), array('inline' => false));
?>
<script type="text/javascript"> 
//	var ar=new Array(33,34,35,36,37,38,39,40);
var ar=new Array(38,40);

jQuery(document).keydown(function(e) {
	var key = e.which;
	if($.inArray(key,ar) > -1) {
	  switch(key){
		case 40:
			$('#timeline #next').trigger('click');
			break;
		case 38:
			$('#timeline #prev').trigger('click');
			break;
	  
	  }
	
	  e.preventDefault();
	  return false;
	}
});

jQuery(document).ready(function(e){
	$('#issues').scrollTop(0);
	
	jQuery().timelinr({
		orientation: 'vertical',
		issuesSpeed: 300,
		datesSpeed: 500,
		issuesSpeed: 500,
		arrowKeys: true,
		startAt: 1
	});
});
</script>


<div class="wrap">
	<div class="content_top"></div>
	<div class="content_middle">
		<div class="linha-titulo-home titulo historia">
			<div class="linha"></div>
			<div class="titulo">história</div>
			<div class="linha"></div>
		</div>
		<div class="sub-titulo titulo historia">
			<p class="titulo">Ginásio do ibirapuera</p>
			<p class="data">27/11 <span>A</span> 01/12</p>
			<br />
			<?php echo $this->Element('midias'); ?>
		</div>
		<br /><br />
		<div class="content_middle historia">
			<?php foreach($timelines AS $key=>$timeline):?>
				<?php 
					echo $this->Html->image(
						array('controller'=>'thumbs','?'=>array('src'=>$timeline['Timeline']['image'],'size'=>'629*473')),
						array('alt'=>'',)
					);
				?>
				<h3><?php echo $timeline['Timeline']['titulo'];?></h3>
				<div class="issue_container_text"><?php echo $timeline['Timeline']['conteudo'];?></div>
			<?php endforeach; ?>
		</div>
	</div>
	<br />
	<br />
<div class="content_bottom"></div>
<div class="linha"></div>	
</div>