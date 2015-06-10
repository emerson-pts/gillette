<?php
$this->set('topLink',
	$html->link('Limpar todo o cache', array('action'=>'clearcache',), array('class'=>'topLink'))
);
?>
<div class="box">
	<h4 class="white">Lista de arquivos em cache</h4>
	<div class="grupos index">
		<div class="box-container">
      		<table class="table-short">
      			<thead>
					<tr>
						<td>Arquivo</td>
						<td>Data</td>
						<td class="actions"><?php __('Ações');?></td>
					</tr>
				</thead>
				<tbody>
			<?php
			foreach($arquivos AS $key=>$value):
				if(preg_match('/^\.+$/', $value))continue;
			?>
					<tr>
						<td class="text-align-left">
							<?php echo $value; ?>
						</td>
						<td class="text-align-left">
							<?php echo date('d/m/Y h:i', filemtime($cache_dir.$value));?>
						</td>
						<td>
							<?php
							echo $html->link('Remover',array('action' => 'delete', $value));
							?>
						</td>
					</tr>
				</tbody>
			<?php endforeach; ?>
			</table>
			</div>
	</div>
</div>
