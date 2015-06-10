<?php
?>
<script type="text/javascript">
jQuery(document).ready(function(event){
	jQuery('.slideshow')
		.before('<div id="nav">')
		.cycle({
			fx:     'fade',
			speed:  1000,
			timeout: 13000,
			pager:  '#nav',
			pagerAnchorBuilder: function(idx, slide) {
				// return sel string for existing anchor
				return '#nav li:eq(' + (idx) + ') a';
			}
		})
	;
})
;
</script>
<div class="vitrine-wrap">
	<div class="vitrine">
		<ul id="nav"><?php foreach($home_banners AS $key=>$r):?>
			<li>
				<a href="#" <?php if($key == 3)echo 'class="last"';?>>
					<span class="vitrine-titulo"><?php echo $r['HomeBanner']['titulo'];?></span><br />
					<span class="vitrine-txt"><?php echo $r['HomeBanner']['chamada'];?></span>
				</a>
			</li>
		<?php endforeach;?>
		</ul>
		<div class="slideshow"><?php foreach($home_banners AS $r):?>
			<a href="<?php echo $r['HomeBanner']['url'];?>"><img src="<?php echo $this->base.'/'.$r['HomeBanner']['imagem'];?>" alt="<?php echo $r['HomeBanner']['titulo'];?>" /></a>
		<?php endforeach;?></div>
	</div>
</div>