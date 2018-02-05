<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule/templates/Account/reset.latte

use Latte\Runtime as LR;

class Template91ba113e50 extends Latte\Runtime\Template
{
	public $blocks = [
		'title' => 'blockTitle',
		'content' => 'blockContent',
		'_resetPassword' => 'blockResetPassword',
	];

	public $blockTypes = [
		'title' => 'html',
		'content' => 'html',
		'_resetPassword' => 'html',
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
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockTitle($_args)
	{
		?>Obnovení hesla<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>
    <script>
        window.onload = function () {
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
        }

    </script>
    <br>
    <div class="container">
        <div class="col-md-3"></div>
        <div class="col-md-6">

<?php
		if ($presenter->getParameter('token') && $presenter->getParameter('email')) {
			/* line 31 */
			$this->createTemplate('resetPasswordForm.latte', $this->params, "include")->renderToContentType('html');
		}
		else {
			?><div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('resetPassword')) ?>"><?php
			$this->renderBlock('_resetPassword', $this->params) ?></div><?php
		}
?>
        </div>
        <div class="col-md-3"></div>
    </div>
<?php
	}


	function blockResetPassword($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("resetPassword", "static");
		if ($user_found) {
			/* line 35 */
			$this->createTemplate('securityQuestionForm.latte', $this->params, "include")->renderToContentType('html');
?>

<?php
		}
		else {
			/* line 38 */
			$this->createTemplate('emailVerification.latte', $this->params, "include")->renderToContentType('html');
?>

<?php
		}
?>

<?php
		$this->global->snippetDriver->leave();
		
	}

}
