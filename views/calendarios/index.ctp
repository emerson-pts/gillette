<?php echo $this->set('title_for_layout', 'Programação');  ?>
<div class="wrap">
	<div class="content_top"></div>
			<div class="content_middle">
				<div class="linha-titulo-home titulo programacao">
							<div class="linha"></div>
							<div class="titulo">Programação</div>
							<div class="linha"></div>
						</div>
						<div class="sub-titulo titulo programacao">
							<p class="titulo">Ginásio do ibirapuera</p>
							<p class="data">27/11 <span>A</span> 01/12</p>
							<br />
							<?php echo $this->Element('midias'); ?>
						</div>
				<br /><br /><?php
			if(!empty($calendarios)){
			?>
			
			<table width="100%" class="calendario">
			<?php 
				//Eventos do calendario
				
				foreach($calendarios AS $key=>$calendario){
					?><tr <?php echo ((!isset($this->params['named']['evento']) && $key == 0) || (isset($this->params['named']['evento']) && $this->params['named']['evento'] == $key) ? 'class="active"' : 'class="active"'/*deixe "" e descomente o js para fazer o acordeon*/);?>><?PHP
					?><td class="table-data"><?php
						echo $calendario['Calendario']['data_dia'].' de '.$calendario['Calendario']['data_mes_formatada'].'<br/>'.$calendario['Calendario']['dia_semana_formatado'];			
					?></td><td class="table-evento"><?php
						echo $this->Html->tag('h3',$calendario['Calendario']['titulo']);
						echo $this->Html->div('txt-padrao',$calendario['Calendario']['descricao']);
					?></td>
					</tr>
					<tr>
						<td colspan="2">
							<br />
							<br />
							<hr />
						</td>
					</tr>
					<?php
				}
			?></table><?php
			
			}else{
				echo $this->Html->div('error-message','Não foi encontrado eventos');
			}
		?></div>
	<br />
	<br />
	<div class="content_bottom"></div>
<div class="linha"></div>	
</div>