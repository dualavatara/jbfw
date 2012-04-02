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

	public function show() { return $this->content; }

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

	public function columnHeader($header, $align='center') {
		?>
	<div class="title black" style="line-height:64px;">
		<div id="hrcol" style="text-align:<?php echo $align; ?>"><?php echo $header; ?></div>
	</div>
	<?php
	}
}
