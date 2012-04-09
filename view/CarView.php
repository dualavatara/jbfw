<?php
/**
 * User: dualavatara
 * Date: 4/6/12
 * Time: 10:00 AM
 */

namespace View;

class CarView extends BaseView {
	public $car;

	public function show() {
		$this->start();
		$this->carsProfile($this->car);
		$this->end();
		return parent::show();
	}

	public function carsProfile($car, $left = false) {
		$mainImg = $car->getMainImage();
		?>
	<div class="itemblock" style="width: 50em; height: 15em; font-size: 0.8em;
float: left;<?php if ($left) echo 'margin-right:0.5em'; ?>">
		<div style="width: 18em;padding: 1em 0.5em 0.5em 1em;float:left;">
			<?php
			$link = \Ctl\CarCtl::link('profile', array('id' => $car->id));

			$this->blockMainImg('carsprofile' . $car->id,
				$mainImg,
				'javascript:void(0)',
				$car->flags->check(\CarModel::FLAG_HIT),
				$car->flags->check(\CarModel::FLAG_DESCOUNT),
				'loadCarProfile(\'' . $car->name . '\',\'' . SERVER_URL . $link . '\')',
				false,
				'thumbnail200',
				200,
				200
			);
			?>
		</div>
		<div style="margin-left: 17em;">
			<div><h2><?php echo $car->name; ?></h2></div>
			<div><?php
				$this->blockOtherImg('carsprofile' . $car->id, $car->getOtherImages(), 10000);
				?>
			</div>
			<div style="margin-top: 9em;">
				<div><?php
					$price = $car->getPrices();
					$priceValue = '';
					$type = '';
					if ($price->count()) {
						$priceValue = $price[0]->calcValue(\Session::obj()->currency['course']);
						if ($price[0]->type == \PriceModel::TYPE_SELL) $type = \PriceModel::TYPE_SELL;
						if ($price[0]->type == \PriceModel::TYPE_RENT) $type = \PriceModel::TYPE_RENT;
					}
					if ($priceValue) {
						?>
					<span style="font-size: 1.6em; margin-left: 1em;"><?php echo \Session::obj()->currency['sign']; ?>
						<span
							style="color:red;"><b><?php echo $price[0]->calcValue(\Session::obj()->currency['course']);?></b></span>
					<?php } else echo "&nbsp;"; ?></div>
				<div><?php
					if ($type) {
						if ($type == \PriceModel::TYPE_RENT) {
							?>
							<?php $this->orderButton('#'); ?>
							<?php
						} else {
							$this->requestButton('#');
						}
						;
					};
					?></div>
			</div>
		</div>
		<div style="clear:both;font-size: 0.9em; padding: 1em">
			<h3>Описание</h3>

			<div><?php echo $car->description; ?></div>
		</div>
		<div>
			<?php
			$props = array(
				'кол-во пассажиров' => $car->seats,
				'кол-во дверей' => $car->doors,
				'коробка передач' => $car->flags->check(\CarModel::FLAG_AUTOMAT) ? 'АКП' : 'МКП',
				'кондиционер' => $car->flags->check(\CarModel::FLAG_CONDITIONER) ? 'да' : 'нет',
				'тип' => $car->type_id,
				'расход топлива, л' => $car->fuel,
				'кол-во пассажиров' => $car->seats,
				'кол-во багажа' => $car->baggage,
				'дверей' => $car->doors,
				'мин. возраст' => $car->min_age,
				'объем двигалетя' => $car->volume,

			);
			$pr = array(
				'стоимость доп. пассажира, ' . \Session::obj()->currency['sign'] => $car->price_addseat,
				'страховка, ' . \Session::obj()->currency['sign'] => $car->price_insure,
				'франшиза, ' . \Session::obj()->currency['sign'] => $car->price_franchise,
				'стоимость автокресла 0+, ' . \Session::obj()->currency['sign'] => $car->price_seat1,
				'стоимость автокресла 1, ' . \Session::obj()->currency['sign'] => $car->price_seat2,
				'стоимость автокресла 2-3, ' . \Session::obj()->currency['sign'] => $car->price_seat3,
				'стоимость цепей, ' . \Session::obj()->currency['sign'] => $car->price_chains,
				'стоимость нафигатора, ' . \Session::obj()->currency['sign'] => $car->price_navigator,
				'стоимость залога, ' . \Session::obj()->currency['sign'] => $car->price_zalog,
				'скидка 3-6, ' . \Session::obj()->currency['sign'] => $car->discount1,
				'скидка 7-8, ' . \Session::obj()->currency['sign'] => $car->discount2,
				'скидка 9-15, ' . \Session::obj()->currency['sign'] => $car->discount3,
				'скидка 16-29, ' . \Session::obj()->currency['sign'] => $car->discount4,
				'скидка 30, ' . \Session::obj()->currency['sign'] => $car->discount5,
				'стоимость трансфера в аэропорт, ' . \Session::obj()->currency['sign'] => $car->trans_airport,
				'стоимость трансфера в гостиницу, ' . \Session::obj()->currency['sign'] => $car->trans_hotel,
				'стоимость водителя в сутки, ' . \Session::obj()->currency['sign'] => $car->trans_driver,
				'стоимость при грязной машине, ' . \Session::obj()->currency['sign'] => $car->trans_dirty,
			);
			array_walk($pr, function(&$val) {
				$val = sprintf("%.2f", $val / \Session::obj()->currency['course']);
			});
			$props = $props + $pr;
			$this->blockProperties($props);
			?>
		</div>
	</div>
	<?php
	}
}
