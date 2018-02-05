<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule/templates/Account/signup.latte

use Latte\Runtime as LR;

class Templatea324c9b7cd extends Latte\Runtime\Template
{
	public $blocks = [
		'title' => 'blockTitle',
		'content' => 'blockContent',
		'_flashMessage' => 'blockFlashMessage',
	];

	public $blockTypes = [
		'title' => 'html',
		'content' => 'html',
		'_flashMessage' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('title', get_defined_vars());
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 12');
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 17');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockTitle($_args)
	{
		?>Registrace<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>
    <br>
    <div class="container">
        <div class="col-md-3"></div>
        <div class="col-md-6">
<?php
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["signupForm"];
		?>            <form<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		), false) ?>>
                <div class="row myborder">
<?php
		if ($form->hasErrors()) {
?>                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<?php
			$iterations = 0;
			foreach ($form->errors as $error) {
				?>                        <p><?php echo LR\Filters::escapeHtmlText($error) /* line 12 */ ?></p>
<?php
				$iterations++;
			}
?>
                    </div>
<?php
		}
		?>                    <img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 14 */ ?>/img/logo_websitebuilder.png" alt="FastWeb">
                    <hr>
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('flashMessage')) ?>"><?php $this->renderBlock('_flashMessage', $this->params) ?></div>                    <div class="input-group margin-bottom-20">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user mycolor"></i></span>
                        <input size="60" maxlength="50" class="form-control" placeholder="Uživatelské jméno" type="text"<?php
		$_input = end($this->global->formsStack)["username"];
		echo $_input->getControlPart()->addAttributes(array (
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		))->attributes() ?>>
                    </div>
                    <div class="input-group margin-bottom-20">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock mycolor"></i></span>
                        <input size="60" maxlength="50" class="form-control" placeholder="Heslo"  type="password"<?php
		$_input = end($this->global->formsStack)["password"];
		echo $_input->getControlPart()->addAttributes(array (
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		))->attributes() ?>>
                    </div>
                    <div class="input-group margin-bottom-20">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock mycolor"></i></span>
                        <input size="60" maxlength="50" class="form-control" placeholder="Heslo pro kontrolu" type="password"<?php
		$_input = end($this->global->formsStack)["password2"];
		echo $_input->getControlPart()->addAttributes(array (
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		))->attributes() ?>>
                    </div>
                    <div class="input-group margin-bottom-20">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope mycolor"></i></span>
                        <input size="60" maxlength="254" class="form-control" placeholder="Emailová adresa"  type="text"<?php
		$_input = end($this->global->formsStack)["email"];
		echo $_input->getControlPart()->addAttributes(array (
		'size' => NULL,
		'maxlength' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		'type' => NULL,
		))->attributes() ?>>
                    </div>
                    <div class="input-group margin-bottom-20">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign mycolor"></i></span>
                        <select class="form-control"<?php
		$_input = end($this->global->formsStack)["security_questions"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		))->attributes() ?>><?php echo $_input->getControl()->getHtml() ?></select>
                    </div>
                    <div class="input-group margin-bottom-20">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-exclamation-sign mycolor"></i></span>
                        <input size="60" maxlength="100" class="form-control" placeholder="Odpověď na bezpečnostní otázku"  type="text"<?php
		$_input = end($this->global->formsStack)["security_question_answer"];
		echo $_input->getControlPart()->addAttributes(array (
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
                            <button class="btn-u" type="submit"<?php
		$_input = end($this->global->formsStack)["signup"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'type' => NULL,
		))->attributes() ?>>Registrovat</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="account-already">Pokud už účet máte, stačí se <a href="<?php
		echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("signin")) ?>">přihlásit.</a></p>
                        </div>
                    </div>
                </div>
<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
<?php
	}


	function blockFlashMessage($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("flashMessage", "static");
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>                        <div class="alert alert-<?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 17 */ ?> alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <p><?php echo LR\Filters::escapeHtmlText($flash->message) /* line 19 */ ?></p>
                        </div>
<?php
			$iterations++;
		}
		$this->global->snippetDriver->leave();
		
	}

}
