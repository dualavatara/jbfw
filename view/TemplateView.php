<?php
/**
 * User: dualavatara
 * Date: 3/9/12
 * Time: 8:26 PM
 */
class TemplateView {
	/**
	 * @var string
	 */
	private $template;
	/**
	 * @param string $templateName
	 */
	function __construct($templateName) {
		$this->template = 'tplsrc/' . $templateName;
	}
	public function show($contextVars){
		extract($contextVars);
		ob_start();
		include $this->template;
		return ob_get_clean();
	}
}
