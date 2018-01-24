<?php
// source: C:\xampp\htdocs\websitebuilder\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template55012a8ad2 extends Latte\Runtime\Template
{
	public $blocks = [
		'_flashMessage' => 'blockFlashMessage',
		'scripts' => 'blockScripts',
	];

	public $blockTypes = [
		'_flashMessage' => 'html',
		'scripts' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 6 */ ?>/vendor/bootstrap/css/bootstrap.min.css">
    <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 7 */ ?>/vendor/form-helpers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 9 */ ?>/css/styles.css">

    <title><?php
		if (isset($this->blockQueue["title"])) {
			$this->renderBlock('title', $this->params, function ($s, $type) {
				$_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striphtml', $_fi, $s));
			});
			?> <?php
		}
?> </title>
</head>

<body class="login-bg">
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('flashMessage')) ?>"><?php $this->renderBlock('_flashMessage', $this->params) ?></div><div class="header">
    <div class="container">
        <div class="row">
<?php
		if ($this->global->uiPresenter->isLinkCurrent("Account:*")) {
?>
                <div class="col-md-12">
                    <!-- Logo -->
                    <div class="logo">
                        <h1><a href="">PiticuCMS</a></h1>
                    </div>
                </div>
<?php
		}
		else {
?>
                <div class="col-md-5">
                    <!-- Logo -->
                    <div class="logo">
                        <h1><a href="">PiticuCMS</a></h1>
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-5">
                    <div class="navbar navbar-inverse" role="banner">
                        <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-target="dropdown"><?php
			echo LR\Filters::escapeHtmlText($user->getIdentity()->username) /* line 44 */ ?> <b
                                                class="caret"></b></a>
                                    <ul class="dropdown-menu animated fadeInUp">
                                        <li><a href="">Změna uživatelskych údajů</a></li>
                                        <li><a href="">Změna šablony</a></li>
                                        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Account:out")) ?>">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
<?php
		}
?>
        </div>
    </div>
</div>

<div<?php if ($_tmp = array_filter(['page-content',!$user->isLoggedIn() ? 'container' : NULL])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
    <div class="row">
<?php
		$this->renderBlock('content', $this->params, 'html');
?>
    </div>


<?php
		$this->renderBlock('scripts', get_defined_vars());
?>
</body>
</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 16');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockFlashMessage($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("flashMessage", "static");
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>    <div class="alert-message <?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 16 */ ?>">
        <a class="close" data-dismiss="alert">×</a>

        <p><?php echo LR\Filters::escapeHtmlText($flash->message) /* line 19 */ ?></p>
    </div>
<?php
			$iterations++;
		}
		$this->global->snippetDriver->leave();
		
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="http://code.jquery.com/jquery-3.2.1.js"></script>
        <!-- jQuery UI -->
        <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 73 */ ?>/js/custom.js"></script>
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 74 */ ?>/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 75 */ ?>/form-helpers/js/bootstrap-formhelpers.min.js"></script>
        <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 76 */ ?>/js/forms.js"></script>

<?php
	}

}
