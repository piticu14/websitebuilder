<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule/templates/Account/../../../../app/presenters/templates/Error/410.latte

use Latte\Runtime as LR;

class Templatec4f2a1fe01 extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html><!-- "' --></textarea></script></style></pre></xmp></a></audio></button></canvas></datalist></details></dialog></iframe></listing></meter></noembed></noframes></noscript></optgroup></option></progress></rp></select></table></template></title></video>
<meta charset="utf-8">
<meta name="robots" content="noindex">
<title>Server Error</title>

<style>
    #nette-error { all: initial; position: absolute; top: 0; left: 0; right: 0; height: 70vh; min-height: 400px; display: flex; align-items: center; justify-content: center; z-index: 1000 }
    #nette-error div { all: initial; max-width: 550px; background: white; color: #333; display: block }
    #nette-error h1 { all: initial; font: bold 50px/1.1 sans-serif; display: block; margin: 40px }
    #nette-error p { all: initial; font: 20px/1.4 sans-serif; margin: 40px; display: block }
    #nette-error small { color: gray }
</style>

<div id=nette-error>
    <div>
        <h1>Server Error</h1>

        <p>The page you requested has been taken off the site. We apologize for the inconvenience.</p>

        <p><small>error 410</small></p>
    </div>
</div>

<script>
    document.body.insertBefore(document.getElementById('nette-error'), document.body.firstChild);
</script>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
