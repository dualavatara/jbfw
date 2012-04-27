<?php
/**
 * User: dualavatara
 * Date: 4/22/12
 * Time: 10:18 PM
 */
namespace View\Form;

class Separator extends Field{
	public function __construct() {
		parent::__construct('', '', '');
	}

	public function html() {
		ob_start();
		?>
	<div style="
    margin: 0px -1em;
    color: #fda62d;
    background-color: #fda62d;
    height: 1px;
    border: none;
    border-top: 1px solid #fee53c;
    border-bottom: 1px solid #fee53c;
    height: 1px;
">&nbsp;</div>
	<?php
		return ob_get_clean();
	}
}
