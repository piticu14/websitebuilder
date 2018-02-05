<?php
// source: C:\xampp\htdocs\websitebuilder\app\AccountModule/templates/Account/activation.latte

use Latte\Runtime as LR;

class Template541ef7b620 extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
