<?php echo $form->create('Usuario',array('class'=>'form', 'action' => 'change_pass'));?>
	<h3>Alteração de senha</h3>
	<p>Por favor, preencha o formulário a seguir para alterar sua senha de acesso.</p>
	<fieldset>
		<?php
			echo 
				$form->input('senha',array('type'=>'password','div'=>'input even','value'=>''))
			;
		?>
	</fieldset>
	<?php echo $form->submit('Alterar senha',array('class' => 'big-button', 'div'=>false));?> ou <?php echo $html->link(__('Cancelar', true), '/', array('class' => 'button small red'));?>
<?php echo $form->end();?>
