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
	/**
	 * @var \RealtyModel
	 */
	public $realties;
	public $articles;
	/**
	 * @var \CarModel
	 */
	public $cars;

	public function show() {
		$this->start();

		$this->columnHeader('Лучшие предложения по Черногории');



		?>
	<div id="cars_block" style="display: block;float:left;">
		<?php
		$i = 0;
		foreach ($this->cars as $car) {
			$i++;
			$this->carsBlock($car, $i % 2);
		};
		?>
	</div><?php

		?>
	<div id="realty_block" style="display: block; float:left;width: 60em;">
		<?php
		foreach ($this->realties as $realty) {
			$this->realtyBlock($realty);
		};
		?>
	</div><?php



		$artOut = function ($article) {
			?>
			<a href="<?php echo \Ctl\ArticleCtl::link('article', array('id' => $article->id));?>">
		<h2 class="red"><?php echo $article->name;?></h2>
		<img src="/s/<?php echo $article->photo_preview;?>" alt="<?php echo $article->alt; ?>">
		<p><?php echo $article->content_short;?></p>
			</a>
		<?php
		};
		/** @noinspection PhpUndefinedVariableInspection */
		if ($this->articles->count()) {
			?>
		<div id="article_block" style="float: left;width: 60em;">
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
