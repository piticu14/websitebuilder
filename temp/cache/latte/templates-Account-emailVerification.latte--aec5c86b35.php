<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule\templates\Account\emailVerification.latte

use Latte\Runtime as LR;

class Templateaec5c86b35 extends Latte\Runtime\Template
{
	public $blocks = [
		'_flashMessage' => 'blockFlashMessage',
	];

	public $blockTypes = [
		'_flashMessage' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<script>
    $('#checkEmail').click(function (e) {
        e.preventDefault();
        $.nette.ajax({
            url: <?php echo LR\Filters::escapeJs($this->global->uiControl->link("checkEmail!")) ?>,
            type: "POST",
            data: { emailAddress: $('#email').val() },
            success: function (data) {
                var email = $('#email').val();
<?php
		$email = 'email';
?>
            },
            error: function(jqXHR,status,error) {
                console.log(jqXHR);
                console.log(status);
                console.log(error);
            }
        });
    });
</script>
<?php
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["emailVerificationForm"];
		?><form<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		), false) ?>>
    <div class="row myborder">
<?php
		if ($form->hasErrors()) {
?>        <div class="alert alert-danger alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<?php
			$iterations = 0;
			foreach ($form->errors as $error) {
				?>            <p><?php echo LR\Filters::escapeHtmlText($error) /* line 24 */ ?></p>
<?php
				$iterations++;
			}
?>
        </div>
<?php
		}
		?>        <img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 26 */ ?>/img/logo_websitebuilder.png" alt="FastWeb">
        <hr>
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('flashMessage')) ?>"><?php $this->renderBlock('_flashMessage', $this->params) ?></div>        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope mycolor"></i></span>
            <input id="email" size="60" maxlength="255" class="form-control"
                   placeholder="Emailová adresa vašeho účtu" type="text"<?php
		$_input = end($this->global->formsStack)["email"];
		echo $_input->getControlPart()->addAttributes(array (
		'id' => NULL,
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		))->attributes() ?>>
        </div>
        <br><br><br><br>

        <div class="row">
            <div class="col-md-12 text-center">
                <button  id="checkEmail" class="btn-u" type="submit"<?php
		$_input = end($this->global->formsStack)["reset"];
		echo $_input->getControlPart()->addAttributes(array (
		'id' => NULL,
		'class' => NULL,
		'type' => NULL,
		))->attributes() ?>>Obnovit heslo</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="account-already">Pokud ještě nemáte účet, tady si ho <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("signup")) ?>">vytvoříte.</a></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="account-already">Pokud už účet máte, stačí se <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("signin")) ?>">přihlásit.</a></p>
            </div>
        </div>
<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?></form>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 24');
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 29');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockFlashMessage($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("flashMessage", "static");
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>            <div class="alert alert-<?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 29 */ ?> alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <p><?php echo LR\Filters::escapeHtmlText($flash->message) /* line 31 */ ?></p>
        </div>
<?php
			$iterations++;
		}
		$this->global->snippetDriver->leave();
		
	}

}
