<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule\templates\Account\securityQuestionForm.latte

use Latte\Runtime as LR;

class Templatec51f700fca extends Latte\Runtime\Template
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
    $('#sendPasswordLink').click(function (e) {
        e.preventDefault();
        $.nette.ajax({
            url: <?php echo LR\Filters::escapeJs($this->global->uiControl->link("sendPasswordLink!")) ?>,
            type: "POST",
            data: { user_question_answer: $('#questionAnswer').val(),
                    emailAddress: <?php echo LR\Filters::escapeJs($email) /* line 8 */ ?>,
                    user_security_question: $('#securityQuestion').val()},
            error: function(jqXHR,status,error) {
                console.log(jqXHR);
                console.log(status);
                console.log(error);
            }
        });
    });
</script>
<?php
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["securityQuestionForm"];
		?><form<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		), false) ?>>
    <div class="row myborder">
<?php
		if ($form->hasErrors()) {
?>        <div class="alert alert-danger alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<?php
			$iterations = 0;
			foreach ($form->errors as $error) {
				?>            <p><?php echo LR\Filters::escapeHtmlText($error) /* line 22 */ ?></p>
<?php
				$iterations++;
			}
?>
        </div>
<?php
		}
		?>        <img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 24 */ ?>/img/logo_websitebuilder.png" alt="FastWeb">
        <hr>
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('flashMessage')) ?>"><?php $this->renderBlock('_flashMessage', $this->params) ?></div>        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope mycolor"></i></span>
            <input id="securityQuestion" size="60" maxlength="255" class="form-control"
                   placeholder="Bezpečnostní otázka k vašému účtu" type="text" value="<?php echo LR\Filters::escapeHtmlAttr($security_question) /* line 35 */ ?>" disabled<?php
		$_input = end($this->global->formsStack)["security_question"];
		echo $_input->getControlPart()->addAttributes(array (
		'id' => NULL,
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		'value' => NULL,
		'disabled' => NULL,
		))->attributes() ?>>
        </div>
        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope mycolor"></i></span>
            <input id="questionAnswer" size="60" maxlength="100" class="form-control"
                   placeholder="Odpověď na bezpečnostní otázku" type="text" required<?php
		$_input = end($this->global->formsStack)["security_question_answer"];
		echo $_input->getControlPart()->addAttributes(array (
		'id' => NULL,
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		'required' => NULL,
		))->attributes() ?>>
        </div>
        <br><br><br><br>

        <div class="row">
            <div class="col-md-12 text-center">
                <button id="sendPasswordLink"  class="btn-u" type="submit"<?php
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
		?></form><?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 22');
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 27');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockFlashMessage($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("flashMessage", "static");
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>            <div class="alert alert-<?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 27 */ ?> alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <p><?php echo LR\Filters::escapeHtmlText($flash->message) /* line 29 */ ?></p>
            </div>
<?php
			$iterations++;
		}
		$this->global->snippetDriver->leave();
		
	}

}
