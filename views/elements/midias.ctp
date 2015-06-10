<?php
//MIDIAS SOCIAIS
$midias_sociais = Configure::read("midias_sociais");
if(!empty($midias_sociais)){
	?><div class="midias-sociais"><?php
		foreach($midias_sociais as $key=>$midia){
			if(!empty($midia)){
				
				echo $this->Html->link(
					$this->Html->image($key.'.png',array("alt" => $key, 'class' => $key)),
					$midia,
					array('target'=>'_blank','escape'=>false)
				);
			}
		}
	?></div><?php
}
?><br class="clear" />
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.midias-sociais img.facebook').after('<span style="line-height: 15px; font-size: 12px; font-family: verdana; color:#787f82; text-transform: uppercase; margin: 9px 36px 0 23px; ">ATPChallengerTourFinals</span>');
	jQuery('.midias-sociais img.twitter').after('<span style="line-height: 15px; font-size: 12px; font-family: verdana; color:#787f82; text-transform: uppercase; margin: 9px 36px 0 23px; ">@atpchfinals</span>');
})
</script>