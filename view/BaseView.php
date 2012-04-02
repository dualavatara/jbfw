<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 1:47 AM
 */

namespace View;

class BaseView implements IView {
	public $content;

	public function start() {
		ob_start();
	}

	public function end() {
		$this->content = ob_get_clean();
	}

	public function show() {
		return $this->content;
	}

	public function titleStars($title, $numStars) {
		?>
	<h2><?php echo $title; ?>
		<?php
		for ($i = 0; $i < $numStars; $i++) {
			?>
			<img src="/static/img/icons/star.png">
			<?php
		}
		?></h2>
	<?php
	}

	public function columnHeader($header, $align = 'center') {
		?>
	<div class="title black" style="line-height:64px;">
		<div id="hrcol" style="text-align:<?php echo $align; ?>"><?php echo $header; ?></div>
	</div>
	<?php
	}

	public function requestButton($href, $onClick = '') {
		?>
	<a href="<? echo $href; ?>" onclick="<? echo $onClick; ?>"><img src="/static/img/buttons/request.png"></a>
	<?php
	}

	public function phoneBlock() {
		?>
	<div style="float: right;width: 18em;position: relative;">
		<img src="/static/img/icons/phone.png" width="40" height="40"
			style="position: absolute;top: 1.5em;">
		<h3 style="margin-left: 3em;margin-bottom:0.2em;">Остались вопросы?</h3>
		<div style="margin-left: 2em;text-align: center;border: none;border-radius: 1em;padding: 1em;padding: 1px;background-color: #cccccc">
		<div style="text-align: center;border: solid #ffffff 2px;border-radius: 1em;padding: 0.6em;background-color: #60a819">
			<h2 style="color: white;margin:0;"><?php echo \Settings::obj()->get()->getPhone1(); ?></h2>
		</div>
		</div>
	</div>
	<?php
	}
}
