<?php
echo $this->Html->css(array('chaves'), null, array('inline' => false));

$depth = 0;
do{
   $rodadas_extract = Set::extract(str_repeat('/chaves', $depth).'/.', $rodadas);
   $depth++;

   if(!empty($rodadas_extract[0])){
	   $rodadas_processadas[] = $rodadas_extract;
   }
}while(!empty($rodadas_extract[0]));

$count_rodadas_processadas = count($rodadas_processadas);

$chaveJogoSpace = array(
   1,
   4,
   10,
   22,
   46,
   90,
);
?>
<script>
$(document).ready(function(){
	var chave_rodada_atual = chave_rodada_inicial = <?php echo ($count_rodadas_processadas-1);?>;
	$('.chaves-wrap').scrollTo($('#chaves-rodada-' + chave_rodada_atual));

	$('table.chaves th[scrollTo]').click(function(){
		$.scrollTo($('#' + $(this).attr('scrollTo')), 500, {offset: {top:-50}});
	});
	
	$('#chaves-rodada-back, #chaves-rodada-next')
		.hover(
			function(){
				if(
					($(this).attr('id') == 'chaves-rodada-back' && chave_rodada_atual < chave_rodada_inicial)
					||
					($(this).attr('id') == 'chaves-rodada-next' && chave_rodada_atual > 2)
				){
					$(this).addClass('hover');
				}
			},
			function(){$(this).removeClass('hover');}
		)
		.click(function(){
			if($(this).attr('id') == 'chaves-rodada-back' && chave_rodada_atual < chave_rodada_inicial){
				chave_rodada_atual++;
			}else if($(this).attr('id') == 'chaves-rodada-next' && chave_rodada_atual > 2){
				chave_rodada_atual--;
			}
			
			if(
				($(this).attr('id') == 'chaves-rodada-back' && chave_rodada_atual == chave_rodada_inicial)
				||
				($(this).attr('id') == 'chaves-rodada-next' && chave_rodada_atual == 2)
			){
				$(this).removeClass('hover');
			}		
						
			$('.chaves-wrap').scrollTo($('#chaves-rodada-' + chave_rodada_atual), 500, {onAfter: function(){
				$.scrollTo($('#chaves-rodada-' + chave_rodada_atual), 500, {offset: {top:-50}});
			}});
		})
	;
	
	$(document).keydown(function(event){
		if (event.keyCode == 39) { 
		   $('#chaves-rodada-next').trigger('click');
		}
		if (event.keyCode == 37) { 
		   $('#chaves-rodada-back').trigger('click');
		}
	});
	
});

</script>
<div class="content_top"></div>
<div class="content_middle">
    <div class="linha-titulo-home"><?php
			echo $this->Html->tag('h2',(isset($menu_ativo[0]['Sitemap']['label']) ?$menu_ativo[0]['Sitemap']['label'] : __('Chaves', true)));
	       	echo $this->element('vendas');
	        echo $this->Html->tag('br','',array('class'=>'clear'));
	        
	?></div><br />
	<div class="noticia-conteudo">
		<ul class="chaves-grupos"><?php foreach($chaves AS $key=>$chave): 
		?>
			<li style="width: <?php echo floor(100/count($chaves));?>%;<?php if($key+1 == count($chaves)){echo 'margin-right: 0;';}?>"><a href="<?php echo $this->base;?>/<?php echo preg_replace('/\/.*$/', '', (isset($this->params['originalArgs']['params']['url']['url']) ? $this->params['originalArgs']['params']['url']['url'] : $this->params['url']['url']));?>/<?php echo $chave['Jogo']['friendly_url'];?>"><?php echo $chave['Jogo']['titulo'];?></a></li>
		<?php endforeach; ?></ul>
		<br class="clear" />
		<div class="chave-grupo-atual"><?php echo $chave_atual['Jogo']['titulo'];?></div>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td id="chaves-rodada-back" class="chaves-rodada-back" rowspan="2"></td>
				<td class="chaves-rodada-back-space" rowspan="2"></td>
				<td>
					<div class="chaves-wrap">
						<div class="chaves-container">
							<table class="chaves jogadores-<?php echo $chave_atual['Jogo']['qtd_jogadores_equipe'];?>" cellspacing="0" cellpadding="0">
								<tr>
									<?php for($i = $count_rodadas_processadas-1; $i>=0; $i--):?>
										<th scrollTo="chaves-rodada-<?php echo $i;?>"><?php echo $rodadas_processadas[$i][0]['Jogo']['titulo'];?></th>
										<?php if($i>0):?><td class="spacer"></td><?php endif;?>
									<?php endfor;?>
								</tr>
								<tr>
									<?php for($i = $count_rodadas_processadas-1; $i>=0; $i--):?>
									<td>
									<?php
									foreach($rodadas_processadas[$i] AS $jogo):
										echo str_repeat('<div class="chave-jogo-space"></div>', current($chaveJogoSpace));
									?>	
										<div id="chaves-rodada-<?php echo $i;?>" class="chave-jogo <?php if($i == 0){echo 'final';}?>">
											<div class="chave-jogador-1 <?php if(!empty($jogo['Equipe1Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')]) && is_numeric($jogo['Equipe1Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){ echo 'chave-jogador-cabeca';}?>">
												<?php 
												if(!empty($jogo['Jogo']['equipe1_jogador1_id'])){
													echo $this->Html->image('flags/'.strtolower($jogo['Equipe1Jogador1']['pais_iso_2']).'.png').'&nbsp;&nbsp;';
													if ($i == ($count_rodadas_processadas-1) && !empty($jogo['Equipe1Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){
														echo '('.$jogo['Equipe1Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')].') ';
													}
													
													if ($i == ($count_rodadas_processadas-1)){
														echo $jogo['Equipe1Jogador1']['nome'];
													}else{
														echo preg_replace('/^.* /', '', trim($jogo['Equipe1Jogador1']['nome']));
													}
													if(!empty($jogo['Jogo']['equipe1_placar'])){
														echo ' '.$jogo['Jogo']['equipe1_placar'];
													}
												}
											?></div>
											<?php if($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2):?>
												<div class="chave-jogador-1 <?php if(!empty($jogo['Equipe1Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')]) && is_numeric($jogo['Equipe1Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){ echo 'chave-jogador-cabeca';}?>">
												<?php 
												if(!empty($jogo['Jogo']['equipe1_jogador2_id'])){
													echo $this->Html->image('flags/'.strtolower($jogo['Equipe1Jogador2']['pais_iso_2']).'.png').'&nbsp;&nbsp;';
													if ($i == ($count_rodadas_processadas-1) && !empty($jogo['Equipe1Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){
														echo '('.$jogo['Equipe1Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')].') ';
													}
													if ($i == ($count_rodadas_processadas-1)){
														echo $jogo['Equipe1Jogador2']['nome'];
													}else{
														echo preg_replace('/^.* /', '', trim($jogo['Equipe1Jogador2']['nome']));
													}
												}
												?>
												</div>
											<?php endif;?>
												<div class="chave-jogador-divider"></div>
											<?php
											if($i != 0):
											?>
												<div class="chave-jogador-2 <?php if(!empty($jogo['Equipe2Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')]) && is_numeric($jogo['Equipe2Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){ echo 'chave-jogador-cabeca';}?>">
													<?php 
													if(!empty($jogo['Jogo']['equipe2_jogador1_id'])){
														echo $this->Html->image('flags/'.strtolower($jogo['Equipe2Jogador1']['pais_iso_2']).'.png').'&nbsp;&nbsp;';
														if ($i == ($count_rodadas_processadas-1) && !empty($jogo['Equipe2Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){
															echo '('.$jogo['Equipe2Jogador1']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')].') ';
														}
														if ($i == ($count_rodadas_processadas-1)){
															echo $jogo['Equipe2Jogador1']['nome'];
														}else{
															echo preg_replace('/^.* /', '', $jogo['Equipe2Jogador1']['nome']);
														}
													}
													if(!empty($jogo['Jogo']['equipe2_placar'])){
														echo ' '.$jogo['Jogo']['equipe2_placar'];
													}
												?></div>
												<?php if($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2):?>
													<div class="chave-jogador-2 <?php if(!empty($jogo['Equipe2Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')]) && is_numeric($jogo['Equipe2Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){ echo 'chave-jogador-cabeca';}?>">
													<?php 
													if(!empty($jogo['Jogo']['equipe2_jogador2_id'])){
														echo $this->Html->image('flags/'.strtolower($jogo['Equipe2Jogador2']['pais_iso_2']).'.png').'&nbsp;&nbsp;';
														if ($i == ($count_rodadas_processadas-1) && !empty($jogo['Equipe2Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')])){
															echo '('.$jogo['Equipe2Jogador2']['cabeca_chave'.($chave_atual['Jogo']['qtd_jogadores_equipe'] == 2 ? '_dupla' : '')].') ';
														}
														if ($i == ($count_rodadas_processadas-1)){
															echo $jogo['Equipe2Jogador2']['nome'];
														}else{
															echo preg_replace('/^.* /', '', $jogo['Equipe2Jogador2']['nome']);
														}
													}
													?>
													</div>
												<?php endif;?>
											<?php endif;?>
										</div>
									<?php
										echo str_repeat('<div class="chave-jogo-space"></div>', current($chaveJogoSpace));
									endforeach;
									?>
									</td>
									<?php if($i>0):?><td class="spacer"></td><?php endif;?>
								<?php
									//Se não é a penúltima rodada, avança o espaçamento
									if($i > 1){
										next($chaveJogoSpace);
									}
							
								endfor;?>
								</tr>
							</table>
						</div>
					</div>
				</td>
				<td class="chaves-rodada-next-space" rowspan="2"></td>
				<td id="chaves-rodada-next" class="chaves-rodada-next" rowspan="2"></td>
			</tr>
		</table>
	</div>	
</div>
<div class="content_bottom"></div>
<?php

function rodadas_processa($rodadas, $depth = 0){
	$return = array();
	var_dump();
	exit;
	print_R(Set::flatten($rodadas));exit;
}