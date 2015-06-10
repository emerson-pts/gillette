<?php
if($msg !== true){
	echo $msg;
}else{
	if(empty($this->params['isAjax'])){
		if(!empty($back)){
			$this->set('topLink', $html->link('Voltar', $back,array('title'=> 'Voltar', 'escape' => false, 'class'=>'topLink')));
		}
	}

	?><section class="grid_12">
	<div class="block-border"><div class="block-content no-padding">
		<h1><?php echo __('Registro de atualizações');?></h1>
		<div class="block-controls"></div>
		<div>
			<table class="table no-margin no-margin-bottom" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th width="130">Data</th>
						<th width="120">Nome</th>
						<th width="80">Ação</th>
						<th width="180">Tabela</th>
						<th class="text-align-left">Detalhe</th>
					</tr>
				</thead>
					<tbody>
						<?php
						foreach($data AS $r){
							echo '<tr>
								<td>'.$r['Log']['created'].'</td>
								<td>'.$r['Usuario']['nome'].'</td>
								<td>'.$r['Log']['action_formatada'].'</td>
								<td>'.$r['Log']['model'].' (cód. '.$r['Log']['model_id'].')</td>
								<td class="text-align-left"><div class="text-diff">';
								echo implode('<br />',$r['Log']['change_formatada']).'<br />';
/*
							foreach($r['Log']['change_array'] AS $key=>$value){
								echo '<b>'.Inflector::humanize($value['field']).'</b>: '.$formatacao->diff($value['from'], $value['to']).'<br />';
							}
*/
							echo '</div></td>
							</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div></div>
	</section>
<?php
}