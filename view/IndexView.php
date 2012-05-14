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



		$this->articlesPreviewBlock($this->articles);

		$this->end();
		return $this->content;
	}


}
