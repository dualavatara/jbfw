<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 1:18 AM
 */

namespace View;

class IndexView extends BaseView {

	/**
	 * params
	 */
	public $bannersLeft;
	public $realties;
	public $articles;

	public function show() {
		$this->start();

		$this->columnHeader('Лучшие предложения по Черногории');

		foreach ($this->realties as $realty) {
			$this->realtyBlock($realty);
		};


		$artOut = function ($article) {
			?>
		<h2 class="red"><?php echo $article->name;?></h2>
		<img src="/s/<?php echo $article->photo_preview;?>">
		<p><?php echo $article->content_short;?></p>
		<?php
		};
		/** @noinspection PhpUndefinedVariableInspection */
		if ($this->articles->count()) {
			?>
		<div id="article_block">
			<div class="alcol frame"><?php $artOut($this->articles[0]);?></div>
			<?php
			if ($this->articles->count() == 3) {
				?>
				<div class="arcol">
					<div class="alcol frame"><?php $artOut($this->articles[1]);?></div>
					<div class="arcol frame"><?php $artOut($this->articles[2]);?></div>
				</div>
				<?php
			} else if ($this->articles->count() == 2) {
				?>
				<div class="arcol frame"><?php $artOut($this->articles[1]);?></div>
				<?php
			};
			?>
		</div>
		<?php
		}
		;
		$this->end();
		return $this->content;
	}


}
