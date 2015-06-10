<?php
	echo $form->create('Usuario' , array('action' => 'login', 'class' => 'form with-margin'));
?>
		<?php
			echo $this->Form->input('email',array('label'=>'<span class="big">E-mail</span>', 'class' => 'full-width', 'div' => 'inline-small-label with-margin', 'autocomplete' => 'off',));
			echo $this->Form->input('senha',array('label'=>'<span class="big">Senha</span>', 'class' => 'full-width', 'div' => 'inline-small-label with-margin', 'type' => 'password', 'autocomplete' => 'off',));
			echo $this->Form->button('LOGIN', array('type' => 'submit', 'class' => 'float-right', 'autotab'=> true));
		?>
<?php echo $form->end();?>
<br class="clear" />
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#UsuarioEmail').focus();

	// We'll catch form submission to do it in AJAX, but this works also with JS disabled
	jQuery('#UsuarioLoginForm').ajaxForm({
		cache: false, 
		dataType: "json",
		beforeSubmit: function(formData, jqForm, options) { 
			// Check fields
			var login = jQuery('#UsuarioEmail').val();
			var pass = jQuery('#UsuarioSenha').val();
			
			if (!login || login.length == 0)
			{
				jQuery('#login-block').removeBlockMessages().blockMessage('<?php __('Por favor, digite seu usuário');?>', {type: 'warning'});
				return false;
			}
			else if (!pass || pass.length == 0)
			{
				jQuery('#login-block').removeBlockMessages().blockMessage('<?php __('Por favor, digite sua senha');?>', {type: 'warning'});
				return false;
			}
			else
			{
				var submitBt = jqForm.find('button[type=submit]');
				submitBt.disableBt();
				jqForm.sendTimer = new Date().getTime();

				// Message
				jQuery('#login-block').removeBlockMessages().blockMessage('<?php __('Realizando autenticação no sistema...');?>', {type: 'loading'});

				return true;
			}

			return false;
		},
		
		success: function(data, statusText, xhr, jqForm){
			if (data.valid)
			{
				// Message
				jQuery('#login-block').removeBlockMessages().blockMessage(data.msg, {type: 'success'});

				// Small timer to allow the 'cheking login' message to show when server is too fast
				var receiveTimer = new Date().getTime();
				if (receiveTimer-jqForm.sendTimer < 500)
				{
					setTimeout(function()
					{
						document.location.href = data.redirect;
						
					}, 500-(receiveTimer-jqForm.sendTimer));
				}
				else
				{
					document.location.href = data.redirect;
				}
			}
			else
			{
				// Message
				jQuery('#login-block').removeBlockMessages().blockMessage(data.error || '<?php __('Aconteceu um erro inexperado. Por favor, tente novamente.');?>', {type: 'error'});
				var submitBt = jqForm.find('button[type=submit]');
				submitBt.enableBt();
			}
		}
		
	
	});
});

</script>