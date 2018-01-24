<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule/templates/Account/signup.latte

use Latte\Runtime as LR;

class Templatea324c9b7cd extends Latte\Runtime\Template
{
	public $blocks = [
		'title' => 'blockTitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'title' => 'html',
		'content' => 'html',
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
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 13');
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
    <div class="page-content container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-wrapper">
                    <div class="box">
                        <div class="content-wrap">
                            <h6>Registrace</h6>
<?php
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["signupForm"];
		?>                            <form<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		), false) ?>>
<?php
		if ($form->hasErrors()) {
?>                                <ul class="alert-message error">
<?php
			$iterations = 0;
			foreach ($form->errors as $error) {
				?>                                    <li><?php echo LR\Filters::escapeHtmlText($error) /* line 13 */ ?></li>
<?php
				$iterations++;
			}
?>
                                </ul>
<?php
		}
		?>                                <input class="form-control"<?php
		$_input = end($this->global->formsStack)["username"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		))->attributes() ?>>
                                <input class="form-control"<?php
		$_input = end($this->global->formsStack)["email"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		))->attributes() ?>>
                                <input class="form-control"<?php
		$_input = end($this->global->formsStack)["password"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		))->attributes() ?>>
                                <input class="form-control"<?php
		$_input = end($this->global->formsStack)["password2"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		))->attributes() ?>>

                                <div class="action">
                                    <input class="btn btn-primary signup"<?php
		$_input = end($this->global->formsStack)["send"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		))->attributes() ?>>
                                </div>
<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>                            </form>
                        </div>
                    </div>
                    <div class="already">
                        <p>Pokud už účet máte, stačí se</p>
                        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("default")) ?>">přihlásit.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
	}

}
