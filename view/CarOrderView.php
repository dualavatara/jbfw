<?php
/**
 * User: dualavatara
 * Date: 5/1/12
 * Time: 2:45 PM
 */
namespace View;

class CarOrderView extends BaseView {
	public $step;
	public $car;
	public $resorts;
	public $places;
	public $carrentoffice;

	function __construct($step = '') {
		if (!$this->step) $this->step = 'step1'; else $this->step = $step;
	}


	public function show() {
		$this->start();

		$fn = $this->step;
		$this->$fn();

		$this->end();
		return parent::show();
	}

	public function step1() {
		?>
	<div style="background-color: #efefef;padding: 1em;">
		<h2><?php echo $this->car->name; ?></h2>
		<img src="/static/img/badge/order_banner.png">
		<?php
		$this->step1top();
		?>
	</div>
	<div class="carorder" style="
		background: url('/static/img/bg/popup_topshade.png') repeat-x top;
		padding: 1em;
		overflow: auto;
		height: 180px;
		"><?php $this->info(); ?>
	</div>
	<hr size="1px">
	<?php
	}

	public function step2() {
		?>
	<div style="background-color: #efefef;padding: 1em;">
		<h2><?php echo $this->car->name; ?></h2>
		<img src="/static/img/badge/order_banner.png">
		<?php
		$this->step2top();
		?>
	</div>
	<div class="carorder" style="
		background: url('/static/img/bg/popup_topshade.png') repeat-x top;
		padding: 1em;
		overflow: auto;
		height: 180px;
		"><?php $this->info(); ?>
	</div>
	<hr size="1px">
	<?php
	}

	public function step1top() {
		$fromDate = \Session::obj()->form['place_from']['date'];
		$toDate = \Session::obj()->form['place_to']['date'];
		?>
	<form action="/carorder2/<?php echo $this->car->id; ?>" id="step1form">
		<table width="100%" cellspacong="0" cellpadding="0" border="0">
			<tr>
				<td style="padding-right: 1em; padding-bottom: 0.5em;border-right: solid 1px #cccccc;border-bottom: solid 1px #cccccc;"><?php $this->placeFormFrom(); ?></td>
				<td style="padding-left: 1em; padding-bottom: 0.5em;border-bottom: solid 1px #cccccc;"><?php $this->placeFormTo(); ?></td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="order[navigator]" value="1">
					<label for="order[navigator]">Навигатор
						<span style="font-weight: bold; color: red">
							<?php echo ceil($this->car->price_navigator / \Session::obj()->currency['course']);?>
						</span>
						<?php echo \Session::obj()->currency['sign']; ?> / сутки
					</label><br>
					<input type="checkbox" name="order[chair]" value="1">
					<label for="order[chair]">Детское кресло
						<span style="font-weight: bold; color: red">
							<?php echo ceil($this->car->price_seat1 / \Session::obj()->currency['course']);?>
						</span>
						<?php echo \Session::obj()->currency['sign']; ?> / сутки
					</label><br>
					<input type="checkbox" name="order[driver]" value="1">
					<label for="order[driver]">Водитель
						<span style="font-weight: bold; color: red">
							<?php echo ceil($this->car->trans_driver / \Session::obj()->currency['course']);?>
						</span>
						<?php echo \Session::obj()->currency['sign']; ?> / сутки
					</label><br>
				</td>
				<td valign="center" align="center">
					<?php
					$price = $this->car->getPrices();
					$priceValue = '';
					$type = '';
					if ($price->count()) {
						$priceValue = $price[0]->calcValue(\Session::obj()->currency['course']);
						if ($price[0]->type == \PriceModel::TYPE_SELL) $type = \PriceModel::TYPE_SELL;
						if ($price[0]->type == \PriceModel::TYPE_RENT) $type = \PriceModel::TYPE_RENT;
					}
					if ($priceValue) {

						/*if ($fromDate && $toDate) {
							$text = '<span
					style="color:red;"><b>' . $this->car->calcPricesDated($fromDate, $toDate, \Session::obj()->currency['course'], \PriceModel::TYPE_RENT) . '</b></span> ' . \Session::obj()->currency['sign'];
							$text .= '<div style="font-size: 0.6em; color: #555;text-align: right;">с ' . $fromDate . ' по ' . $toDate . ' </div>';
						} else {
							$text = 'от <span
					style="color:red;"><b>' . $price[0]->calcValue(\Session::obj()->currency['course']) . '</b></span> ' . \Session::obj()->currency['sign'] . ' в сутки';
							$text .= '<div style="font-size: 0.6em; color: #555;text-align: right;">до 01.03.2012 </div>';
						}*/
					}
					?>
					<span style="font-size: 1.6em; "><?php echo $text;?></span>
					<?php $this->orderButton('javascript:void(0);', "return orderSubmit".$this->step."(". $this->car->id .");") ?></td>
			</tr>
		</table>
	</form>
	<?php
	}

	public function step2top() {
		$form = \Session::obj()->order;
		$fromDate = $form['place_from']['date'];
		$toDate = $form['place_to']['date'];

		$order = \Session::obj()->order;
		if (($order['place_to']['hour']>12)
			|| ($order['place_to']['hour'] == 12 && $order['place_to']['minute'] > 0)) {
			$td = new \DateTime($toDate);
			$td->add(new \DateInterval('P1D'));
			$toDate = $td->format('d.m.Y');
		}
		$f = new \DateTime($fromDate);
		$t = new \DateTime($toDate);


		$days = $f->diff($t, true)->days;
		?>
	<form action="/carorder3/<?php echo $this->car->id; ?>" id="step1form">
		<table width="100%" cellspacong="0" cellpadding="0" border="0">
			<tr colspan="2">
				<td colspan="2">
				<table width="100%" cellspacong="0" cellpadding="0" border="0">
					<tr>
						<td align="center"><a href="javascript:void(0);" onclick="return orderSubmit(<?php echo $this->car->id; ?>);"><?php echo $fromDate; ?></a> </td>
						<td align="center">Дней: <?php echo $days; ?></td>
						<td align="center"><a href="javascript:void(0);" onclick="return orderSubmit(<?php echo $this->car->id; ?>);"><?php echo $toDate; ?></a> </td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php $this->credetialsForm(); ?></td>
			</tr>
			<tr>
				<td nowrap>
					<table width="100%">
					<?php


					if (\Session::obj()->order['navigator']) {
						$p = ($this->car->price_navigator/ \Session::obj()->currency['course']) * $days;
						echo '<tr><td>Навигатор</td><td align="right"><span style="font-weight: bold; color: red">'.ceil($p).'</span> '.\Session::obj()->currency['sign'] . '</td></tr>';
					}
					if (\Session::obj()->order['chair'])
					{
						$p = ($this->car->price_seat1/ \Session::obj()->currency['course']) * $days;
						echo '<tr><td>Детское кресло </td><td align="right"><span style="font-weight: bold; color: red">'.ceil($p).'</span> '.\Session::obj()->currency['sign'] . '</td></tr>';
					}
					if (\Session::obj()->order['driver'])
					{
						$p = ($this->car->trans_driver/ \Session::obj()->currency['course']) * $days;
						echo '<tr><td>Водитель </td><td align="right"><span style="font-weight: bold; color: red">'.ceil($p).'</span> '.\Session::obj()->currency['sign'] . '</td></tr>';
					}

					$p = 0;
					if ($order['place_from']['city'] != $this->car->resort_id) $p = $this->car->trans_airport / \Session::obj()->currency['course'];
					else if ($order['place_from']['place'] != $this->car->place_id) $p += $this->car->trans_hotel / \Session::obj()->currency['course'];

					if ($order['place_to']['city'] != $this->car->resort_id) $p += $this->car->trans_airport /\Session::obj()->currency['course'];
					else if ($order['place_to']['place'] != $this->car->place_id) $p += $this->car->trans_hotel / \Session::obj()->currency['course'];

					if ($p) echo '<tr><td>Доставка </td><td align="right"><span style="font-weight: bold; color: red">'.ceil($p).'</span> '.\Session::obj()->currency['sign'] . '</td></tr>';
					?>
		</table>
					<input type="checkbox" name="agreed" id="agreed" value="1">
					<label for="agreed">С <a href="<?php echo $this->carrentoffice->rent_rules_link; ?>" target="_blank">правилами аренды</a> согласен
					</label>
				</td>
				<td align="center">
					<span style="color: red">Общая стоимость с доставкой</span><br>
					<?php
					$price = $this->car->getPrices();
					$priceValue = '';
					$type = '';
					if ($price->count()) {
						$priceValue = $price[0]->calcValue(\Session::obj()->currency['course']);
						if ($price[0]->type == \PriceModel::TYPE_SELL) $type = \PriceModel::TYPE_SELL;
						if ($price[0]->type == \PriceModel::TYPE_RENT) $type = \PriceModel::TYPE_RENT;
					}
					if ($priceValue) {

						if ($fromDate && $toDate) {
							$perDayAdd = 0;
							if (\Session::obj()->order['navigator']) $perDayAdd += $this->car->price_navigator;
							if (\Session::obj()->order['chair']) $perDayAdd += $this->car->price_seat1;
							if (\Session::obj()->order['driver']) $perDayAdd += $this->car->trans_driver;
							$baseprice = $this->car->calcPricesDated($fromDate, $toDate, \Session::obj()->currency['course'], \PriceModel::TYPE_RENT, $perDayAdd);
							$order = \Session::obj()->order;
							if ($order['place_from']['city'] != $this->car->resort_id) $baseprice += $this->car->trans_airport / \Session::obj()->currency['course'];
							else if ($order['place_from']['place'] != $this->car->place_id) $baseprice += $this->car->trans_hotel / \Session::obj()->currency['course'];
							if ($order['place_to']['city'] != $this->car->resort_id) $baseprice += $this->car->trans_airport /\Session::obj()->currency['course'];
							else if ($order['place_to']['place'] != $this->car->place_id) $baseprice += $this->car->trans_hotel /\Session::obj()->currency['course'];
							$text = '<span
					style="color:red;"><b>' . ceil($baseprice) . '</b></span> ' . \Session::obj()->currency['sign'].'<br>';
						}
					}
					?>
					<span style="font-size: 1.6em; "><?php echo $text;?></span>
					<input type="hidden" name="totalprice" value="<?php echo ceil($baseprice).' '.\Session::obj()->currency['sign'];?>">
					<?php $this->orderButton('javascript:void(0);', "
					if (!$('#name').val()) { alert('Поля отмеченные * должны быть заполнены.');return false;};
					if (!$('#age').val()) { alert('Поля отмеченные * должны быть заполнены.');return false;};
					if (parseInt($('#age').val()) <" .$this->car->min_age. ") { alert('Возраст водителя меньше допустимого для данного предложения.');return false;};
					if (!$('#email').val()) { alert('Поля отмеченные * должны быть заполнены.');return false;};
					if (!$('#phone1').val() && !$('#phone2').val()) { alert('Поля отмеченные * должны быть заполнены.');return false;};
					if (!$('#agreed').attr('checked')) { alert('Для оформления заказа вы должны согласиться с правилами аренды.');return false;};
				openPopup('/carorderfinish/" . $this->car->id . "?' + $('#step1form').serialize(), {id:'step1popup', width:450, height:90, title:'Аренда авто.'});
				closePopup('step1popup')
				") ?></td>
			</tr>
		</table>
	</form>
	<?php
	}

	public function email() {
		$this->start();
		$form = $_REQUEST;
		$order = \Session::obj()->order;

		$resortS = '';
		$resortE = '';
		$placeS = '';
		$placeE = '';

		$this->resorts->get($order['place_from']['city'])->exec();
		if ($this->resorts->count()) $resortS = $this->resorts[0]->name;
		$this->resorts->get($order['place_to']['city'])->exec();
		if ($this->resorts->count()) $resortE = $this->resorts[0]->name;
		$this->places->get($order['place_from']['place'])->exec();
		if ($this->places->count()) $placeS = $this->places[0]->name;
		$this->places->get($order['place_to']['place'])->exec();
		if ($this->places->count()) $placeE = $this->places[0]->name;




		?>
		<div>ID <?php echo $this->car->id.": ". $this->car->name; ?></div>
		<div>Расчитанная сумма: <?php echo $form['totalprice']; ?></div>
		<div>Получение: <?php echo $resortS . ', ' . $placeS . ', '. $order['place_from']['date'] .' ' . $order['place_from']['hour'] . ':' . $order['place_from']['minute']; ?></div>
		<div>Возврат: <?php echo $resortE . ', ' . $placeE . ', ' . $order['place_to']['date'] .' ' . $order['place_to']['hour'] . ':' . $order['place_to']['minute']; ?></div>
		<div><?php if ($order['navigator']) echo "Навигатор"; ?></div>
		<div><?php if ($order['chair']) echo "Детское кресло"; ?></div>
		<div><?php if ($order['driver']) echo "Водитель"; ?></div>
		<div>ФИО <?php echo $form['name'];?></div>
		<div>Возраст <?php echo $form['age'];?></div>
		<div>E-mail <?php echo $form['email'];?></div>
		<div>Телефон 1 <?php echo $form['phone1'];?></div>
		<div>Телефон 2 <?php echo $form['phone2'];?></div>
		<div></div>
		<?php

		$this->end();
		return parent::show();
	}

	public function finish() {
		?>
	<div style="
    padding: 1em;
    color: green;
    text-align: center;
">Спасибо за заказ. <br>
		Заявка принята и в ближайшее время наш менеджер свяжется с Вами.</div>
		<?php
	}

	public function credetialsForm() {
		?>
		<div style="position: relative;margin-top: 1.5em;">
		<span style="position: absolute;top: -1.5em;left: 7.6em;font-size: 0.8em;">
			<i>
				Имя Фамилия как в паспорте
			</i>
		</span>
	<label for="name"
		   style="font-weight: bold; color: black;min-width: 10em; text-align: right;">ФИО <span style="color: red;">*</span></label>
	<input type="text" class="textinput" id="name"
		   name="name"
		   value="" style="width: 26em;padding-left:0.5em;margin-left: 2em;">
		</div>
		<div style="position: relative;margin-top: 1.5em;">
			<span style="position: absolute;top: -1.5em;left: 21.4em;font-size: 0.8em;">
			<i>
				мы вышлем Вам подтверждение брони
			</i>
		</span>
			<label for="age"
				   style="font-weight: bold; color: black;min-width: 10em; text-align: right;">Возраст <span style="color: red;">*</span></label>
			<input type="text" class="textinput" id="age"
				   name="age"
				   value="" style="width: 4em;padding-left:0.5em;margin-left: 0.5em;margin-right: 1em;">
			<label for="email"
				   style="font-weight: bold; color: black;min-width: 10em; text-align: right;">E-mail <span style="color: red;">*</span></label>
			<input type="text" class="textinput" id="email"
				   name="email"
				   value="" style="width: 16.2em;padding-left:0.5em;margin-left: 0.5em;">
		</div>
	<div style="position: relative;margin-top: 1.5em;">
			<span style="position: absolute;top: -1.5em;left: 7.5em;font-size: 0.8em;">
			<i>
				основной
			</i>
		</span>
		<span style="position: absolute;top: -1.5em;left: 27.5em;font-size: 0.8em;">
			<i>
				дополнительный
			</i>
		</span>
			<label for="phone1"
				   style="font-weight: bold; color: black;min-width: 10em; text-align: right;">Телефон <span style="color: red;">*</span></label>
			<input type="text" class="textinput" id="phone1"
				   name="phone1"
				   value="" style="width: 155px;padding-left:0.5em;"><b> или </b>
		<input type="text" class="textinput" id="phone2"
			   name="phone2"
			   value="" style="width:  155px;padding-left:0.5em;">
		</div>
			<?php
	}

	public function placeFormFrom() {
		$fg = new Form\PlaceDateOrderFieldGroup(array(
			'Выберите город получения авто',
			'Выберите место встречи',
			'Доставка к месту',
			'Дата получения',
			'Время получения'
		), 'place_from', $this->resorts->getArray('id', 'name'));
		$fg->formname = 'order';
		$fg->value = \Session::obj()->form['place_from'];
		echo $fg->html();
	}

	public function placeFormTo() {
		$fg = new Form\PlaceDateOrderFieldGroup(array(
			'Выберите город возврата авто',
			'Выберите место встречи',
			'Доставка к месту',
			'Дата возврата',
			'Время возврата'
		), 'place_to', $this->resorts->getArray('id', 'name'));
		$fg->formname = 'order';
		$fg->value = \Session::obj()->form['place_to'];
		echo $fg->html();
	}

	public function info() {
		$flagsRaw = $this->car->getRentIncludedFlags();
		$flags = array();
		foreach ($flagsRaw as $k => $v) if ($this->car->rent_include_flags->check($k)) $flags[$k] = $v;
		if (!empty($flags)) {
			?>
		<div style="background-color: #EFEFEF;
	padding: 0.5em;
	width: 34em;
	margin-bottom: 1em;
float: left;">
			<h3>в стоимость аренды включено:</h3>

			<?php
			$flags = array_chunk($flags, ceil(count($flags) / 2), true);
			foreach ($flags as $f) {
				echo "<ul>";
				foreach ($f as $k => $v) echo "<li>$v</li>";
				echo "</ul>";
			}
			?>
		</div>
		<?php
		}
		;
		?>
	<div style="margin-bottom: 1em;">
		<div style="float: left; width:18em;margin-bottom: 1em;">
			<h3>Описание автомобиля:</h3>
			<?php
			$desc = array(
				'Пассажиров' => $this->car->seats,
				'Ед. багажа' => $this->car->baggage,
				'Двери' => $this->car->doors,
				'Год выпуска' => $this->car->age,
				'Расход топлива' => $this->car->fuel ? $this->car->fuel . 'л/100км' : '',
				'Объем двигателя' => $this->car->volume? $this->car->volume . 'cм&#179;' : '',
			);
			foreach ($desc as $k => $v) {
				if ($v) echo '<div style="float:left; width:10em;">' . $k . '</div><div style="margin-left:10em">' . $v . '</div>';
			}
			?>
		</div>
		<div style="margin-bottom: 1em;">
			<h3>Опции:</h3>

			<?php
			$desc = array(
				'Автоматическая трансмиссия' => $this->car->flags->check(\CarModel::FLAG_AUTOMAT),
				'Камера заднего вида' => $this->car->flags->check(\CarModel::FLAG_CAMERA),
				'Кондиционер' => $this->car->flags->check(\CarModel::FLAG_CONDITIONER),
				'GPS' => $this->car->flags->check(\CarModel::FLAG_SPUTNIK),
			);
			foreach ($desc as $k => $v) {
				if ($v) echo '<div style="">' . $k . '</div>';
			}
			?>
		</div>
	</div>
	<div style="clear: both;margin-bottom: 1em;">
		<h3>Условия аренды:</h3>
		<?php
		$desc = array(
			'минимальный возраст водителя' => $this->car->min_age ? $this->car->min_age . ' г.': '',
			'минимальный стаж' => $this->car->min_exp ? $this->car->min_exp . ' г.' : '',
			'Залог' => ceil($this->car->price_zalog / \Session::obj()->currency['course']) ? ceil($this->car->price_zalog / \Session::obj()->currency['course']) . ' ' . \Session::obj()->currency['sign'] : '',
			'Наличие паспорта' => $this->car->flags->check(\CarModel::FLAG_PASSPORT) ? ' обязательно' : '',
		);
		foreach ($desc as $k => $v) {
			if ($v) echo '<div style="">' . $k . ' ' . $v . '</div>';
		}
		?>
	</div>
	<div>
		<h3>Дополнительные условия и услуги:</h3>
		<?php echo $this->car->description; ?>
	</div>
	<div>
		<table width="100%" cellspacing="2px" cellpadding="2px">
			<thead style="background-color: #fecd36; font-weight: bold;" align="center">
			<tr>
				<td>Цены сезона:</td>
				<td>29+ дн.</td>
				<td>16-29 дн.</td>
				<td>9-15 дн.</td>
				<td>7-8 дн.</td>
				<td>3-6 дн.</td>
				<td>1-2 дн.</td>
			</tr>
			</thead>
			<tbody>
				<?php
				$prices = $this->car->getPrices();
				foreach ($prices as $price) {
					$from = new \DateTime($price->start);
					$from = $price->flags->check(\PriceModel::START_INVALID) ? '' : 'c ' . $from->format("d.m");
					$to = new \DateTime($price->end);
					$to = $price->flags->check(\PriceModel::END_INVALID) ? '' : 'по ' . $to->format("d.m");
					$p = $price->calcValue(\Session::obj()->currency['course']);
					$pd1 = $p - $this->car->discount1 > 0 ? $p - $this->car->discount1 : $p;
					$pd2 = $p - $this->car->discount2 > 0 ? $p - $this->car->discount2 : $p;
					$pd3 = $p - $this->car->discount3 > 0 ? $p - $this->car->discount3 : $p;
					$pd4 = $p - $this->car->discount4 > 0 ? $p - $this->car->discount4 : $p;
					$pd5 = $p - $this->car->discount5 > 0 ? $p - $this->car->discount5 : $p;
					?>
				<tr style="font-weight: bold;" align="center">
					<td><?php echo $from . ' ' . $to; ?></td>
					<td><?php echo $pd5; ?></td>
					<td><?php echo $pd4; ?></td>
					<td><?php echo $pd3; ?></td>
					<td><?php echo $pd2; ?></td>
					<td><?php echo $pd1; ?></td>
					<td><?php echo $p; ?></td>
				</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
	}
}
