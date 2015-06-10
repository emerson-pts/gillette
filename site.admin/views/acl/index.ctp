<?php
$this->viewVars['setup']['topLink']['Recriar lista de permissões disponíveis'] = array('url' => array('action'=>'build_acl', $this->params['pass'][0], $this->params['pass'][1] ), 'htmlAtributes' => array('class'=>'topLink'));
?>
<section class="grid_12">
	<div class="block-border"><div class="block-content">
		<h1>Ajuste de permissões do <?php echo ($this->params['pass'][0] == 'Usuario' ? 'usuário' : 'grupo'). ' '.$current_aro[$this->params['pass'][0]]['nome'];?></h1>
		<table class="table permission">
			<thead>
				<tr>
					<td>Permissão</td>
					<td width="100" class="align-center">Status Atual</td>
					<td class="actions align-center"><?php __('Ações');?></td>
				</tr>
			</thead>
			<tbody>
		<?php
		foreach($aco_tree AS $key=>$value):
		?>
				<tr id="permission_<?php echo $value['id'];?>" class="<?php echo ($value['authorized'] == 1 || $value['authorized_controller'] == 1 ? 'allow' : 'deny');?>">
					<td class="text-align-left">
						<?php echo $value['full_path_traduzido']; ?>
					</td>
					<td class="align-center">
						<?php
							echo ($value['authorized_controller'] == 1 || $value['authorized'] == 1 ? '<a class="table-allow-link current-status">Permitido</a>' : '<a class="table-disallow-link current-status">Negado</a>');
						?>
					</td>
					<td class="align-center"><?php
						if($value['authorized_controller'] == 1)
							echo $html->image('admin/icon-lock.gif').' Permissão fixa';
						else{
							echo $form->create('Acl', array('url'=> array('controller' => 'acl', 'action' => 'set_permission', $this->params['pass'][0], $this->params['pass'][1]), 'id' => 'form_permission_'.$value['id'], 'permission_id' => $value['id'], 'class' => 'SetPermission'));
							echo $form->hidden('full_path', array('value' => $value['full_path']));
							echo $form->hidden('allowdeny', array('value' => ($value['authorized'] == 1 ? 'deny' : 'allow')));
							echo $form->hidden('allowdeny', array('value' => ($value['authorized'] == 1 ? 'deny' : 'allow')));
							//echo $html->link(($value['authorized'] == 1 ? 'Negar' : 'Permitir'),'javascript://Alterar', array('class' =>'table-'.($value['authorized'] == 1 ? 'disallow' : 'allow').'-link submit'));
							echo $html->link('Alterar','javascript://Alterar', array('class' =>'button small submit '.($value['authorized'] == 1 ? 'red' : 'green')));
							echo $form->end();
						}
						?>
					</td>
				</tr>
			</tbody>
		<?php endforeach; ?>
		</table>
	</div></div>
</section>
<script type="text/javascript">
$(document).ready(function(){ 
	$("form.SetPermission a.submit")
		.click(function(event){ 
			blockUIpage("Aguarde, atualizando permissão!");
			$(this).parents("form:first").ajaxSubmit({ 
				cache: false, 
				async: false,
				dataType: "json",
				error: function() { 
					$.unblockUI();
				},
				context: $(this).parents("form:first"),
				success: function(data) { 
					$.unblockUI();
					if (data['msg'] === true){
						//sucesso

						for(i in data['aco_tree']){
							if(data['aco_tree'][i]['authorized_controller']  == ''){
								$('tr#permission_' + data['aco_tree'][i]['id'])
									.removeClass(data['aco_tree'][i]['authorized'] == '1' ? 'deny' : 'allow')
									.addClass(data['aco_tree'][i]['authorized'] == '1' ? 'allow' : 'deny')
									.find('.current-status')
									.removeClass(data['aco_tree'][i]['authorized'] == '1' ? 'table-disallow-link' : 'table-allow-link')
									.addClass(data['aco_tree'][i]['authorized'] == '1' ? 'table-allow-link' : 'table-disallow-link')
									.html(data['aco_tree'][i]['authorized'] == '1' ? 'Permitido' : 'Negado')
								;
								var form = $('form#form_permission_' + data['aco_tree'][i]['id']);
								form.find('#AclAllowdeny').val(data['aco_tree'][i]['authorized'] == '1' ? 'deny' : 'allow');

								form.find('a.submit')
									.removeClass(data['aco_tree'][i]['authorized'] == '1' ? 'green' : 'red')
									.addClass(data['aco_tree'][i]['authorized'] == '1' ? 'red' : 'green')
//									.html(data['aco_tree'][i]['authorized'] == '1' ? 'Negar' : 'Permitir')
								;
							}
						}
						
						blockUImsg('success', 'Permissão atualizada<br />com sucesso.');
					}else{
						//Falha
						if(data === false){
							blockUImsg('error', 'Erro na atualização', 'Por favor, tente novamente.');
						}else{
							blockUImsg('error', 'Erro na atualização', data['msg']);
						}
					}
				}
			});
			event.stopPropagation();
			event.preventDefault();
			return false;
		})
	; 

});
</script>