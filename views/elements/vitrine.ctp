		<div class="vitrine">
			<ul id="slider">
			<?php foreach($vitrines as $vitrine){?>
				<li style="position: relative;">
					<div class="vitrine-img">
						<a href="<?php echo !empty($vitrine['Vitrine']['url'])?$vitrine['Vitrine']['url']:'#';?>">
							<div style="background: url(<?php echo $this->Html->Url('/'.$vitrine['Vitrine']['imagem']);?>) center center no-repeat;">
								
							</div>	
						</a>
						<div class="cinza-vit"></div>
						<div class="texto-vitrine">
							<a href="<?php echo !empty($vitrine['Vitrine']['url'])?$vitrine['Vitrine']['url']:'#';?>">
								<span><?php echo $vitrine['Vitrine']['titulo']; ?></span>
								<span class="txt-chamada"><?php echo $vitrine['Vitrine']['chamada']; ?></span>
								<span>VER+</span>
							</a>	
						</div><!-- Texto Vitrine -->
					</div>
					
				</li>
			<?php } ?>
			</ul>
		</div>	
	<!--
		<p class="vitrine-kcm-prev"><a href="" id="vitrine-kcm-prev"></a></p>
		<p class="vitrine-kcm-next"><a href="" id="vitrine-kcm-next"></a></p>
	-->
	<br class="clear" />
	</div>

<script type="text/javascript">
var slider = new Object();
$(function(){
  slider = $('#slider').bxSlider({
    controls: false,
	auto: false,
	pause: 8000,
	autoHover: true,
	pager: true
  });
  $('#vitrine-kcm-prev').click(function(){
    slider.goToPreviousSlide();
    return false;
});

  $('#vitrine-kcm-next').click(function(){
   slider.goToNextSlide();
    return false;
  });
});

$(window).resize(function(){
	slider.reloadShow()
});
</script>
