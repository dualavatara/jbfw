<?php
/**
 * User: dualavatara
 * Date: 4/2/12
 * Time: 12:50 AM
 */

namespace View;

class RealtyView extends BaseView {
	/**
	 * @var ModelDataWrapper
	 */
	public $realty;

	public function show() {
		$this->start();
		$this->columnHeader($this->realty->name);
		$mainImg = $this->realty->getMainImage();
		?>
	<div style="margin-left: 0.5em">
	<div class="path">
		<a class="grey" href="/">главная</a> /
		<a class="grey" href="<?php echo \Ctl\RealtyCtl::link('index', array());?>">недвижимость</a> / <span
		class="selected">описание отеля или виллы</span>
	</div>
	<div style="margin-top: 2em;">
		<div style="float: left;width: 41em;">
			<div style="width:202px; float:left;position: relative; margin-right: 1em;">
				<script type="text/javascript">
					$(function () {
						// This, or...
						$('a.lightbox<?php echo 'realty' . $this->realty->id; ?>').lightBox(
							{
								txtImage:'Фото',
								txtOf:'из'
							}
						); // Select all links with lightbox class
						// This, or...
					});
				</script>
				<?php
				if ($this->realty->flags->check(\RealtyModel::FLAG_HIT)) {
					?><img class="badgel" src="/static/img/badge/hit.png"><?php
				} else if ($this->realty->flags->check(\RealtyModel::FLAG_DISCOUNT)) {
					?>
					<img class="badgel" src="/static/img/badge/discount.png">
					<?php
				};
				if (isset($mainImg)) {
					?>
					<a href="<?php echo \Ctl\StaticCtl::link('get', array('key' => $mainImg->image)); ?>"
					   class="lightbox<?php echo 'realty' . $this->realty->id; ?>"><img
						src="<?php echo \Ctl\StaticCtl::link('get', array('key' => $mainImg->thumbnail)); ?>"
						width="200" height="200" style="border: solid 1px #808080"></a>
					<?php
				} else {
					?>
					<div class="noimage">Нет изображения</div>
					<?php
				};
				?>
				<?php if ($this->realty->area) { ?>
				<div
					style="background-color: #0082c6; color: #ffffff; font-size: 1.2em; text-align: center;padding: 0.3em;margin: 0.1em;">
					Площадь = <?php echo $this->realty->area; ?>м&#178;</div> <?php }; ?>
				<?php if ($this->realty->plotarea) { ?>
				<div
					style="background-color: #898989; color: #ffffff; font-size: 1.2em; text-align: center;padding: 0.3em;margin: 0.1em;">
					Площадь участка = <?php echo $this->realty->plotarea; ?>м&#178;
				</div><?php }; ?>
			</div>
			<div style="margin-left: 214px">
				<div><?php
					$images = $this->realty->getOtherImages();
					$i = 0;
					foreach ($images as $image) {
						$i++;
						?>
						<div class="thumbnail">
							<a href="/s/<?php echo $image->image; ?>"
							   class="lightbox<?php echo 'realty' . $this->realty->id; ?>">
								<img src="/s/<?php echo $image->thumbnail; ?>" width="50" height="50">
							</a>
						</div>
						<?php
					};
					?>
				</div>
				<?php
				$prices = $this->realty->getPrices(\PriceModel::TYPE_SELL);
				if ($prices->count()) {
					?>
					<div style="float:left; width: 10em; font-size: 1.8em; padding: 1em 0em; ">
						<span style="font-size: 1.2em"><?php echo \Session::obj()->currency['sign']; ?>
							&nbsp;<span
								style="color:red;"><b><?php echo $prices[0]->calcValue(\Session::obj()->currency['course']); ?></b></span></span>
						<?php $this->requestButton('#'); ?>
					</div>
					<?php }; ?>
			</div>

			<div style="float: left;width:41em;">
				<h1>Описание:</h1>

				<div><?php echo $this->realty->description; ?></div>
			</div>
			<div class="dgrey profile" style="float: left;">
				<h3>Общие параметры:</h3>
				<?php
				$params = array(
					'Площадь, м&#178;:' => $this->realty->area,
					'Состояние объекта:' => $this->realty->condstate,
					'Сейф дверь:' => ($this->realty->miscflags->check(\RealtyModel::MISCFLAG_SAFEDOOR) ? 'да' : 'нет'),
					'Сад:' => ($this->realty->miscflags->check(\RealtyModel::MISCFLAG_GARDEN) ? 'да' : 'нет'),
					'Площадь участка, м&#178;:' => $this->plotarea,
					'Этаж:' => $this->realty->floor,
					'Спальни:' => $this->realty->bedrooms,
					'Время постройки:' => $this->realty->age,
				);
				if ($this->realty->type == \RealtyModel::TYPE_VILLA) $hdr = 'Аренда этой Виллы:'; else $hdr = 'Аренда этого отеля:';
				?>
				<table class="properties" border="0" cellpadding="3" cellspacing="0" width="100%">

					<?php
					foreach ($params as $k => $v) {
						if ($v) echo '<tr><td>' . $k . '</td><td>' . $v . '</td></tr>';
					}
					?>

				</table>
			</div>

		</div>
		<div style="float: right;width: 18em;">
			<div style="text-align: center;border: solid #c93232 4px;border-radius: 1em;padding: 1em;">
				<div>курорт: <span class="rating_num"
								   style="color:red;"><?php echo $this->realty->getResort()->name; ?></span><br>
					<a href="<?php echo $this->realty->getResort()->gmaplink; ?>" target="_blank">смотреть на карте</a>
				</div>
			</div>
		</div>
		<?php
		$this->phoneBlock();
?>
	</div>
		<?php
		$prices = $this->realty->getPrices(\PriceModel::TYPE_RENT);
		if ($prices->count()) {
			?>
		<div class="dgrey profile" style="float: left;margin: 1em 0em;">
			<table class="properties" border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr>
					<td colspan="6" style="background-color: #888;
color: white;
text-align: center;
font-size: 1.2em;
font-weight: bold;"><?php echo $hdr; ?></td>
				</tr>
				<tr style="font-weight: bold;background-color: #ffcf00;">
					<td>&nbsp;</td>
					<td>Период</td>
					<td>Сутки, <?php echo \Session::obj()->currency['sign']; ?>/сутки</td>
					<td>Неделя, <?php echo \Session::obj()->currency['sign']; ?>/сутки</td>
					<td>Месяц, <?php echo \Session::obj()->currency['sign']; ?>/сутки</td>
					<td>&nbsp;</td>
				</tr>
				<?php
				foreach ($prices as $price) {
					$start = $price->flags->check(\PriceModel::START_INVALID) ? '' : 'с ' . date_format(new \DateTime($price->start), "d.m.Y");
					$end = $price->flags->check(\PriceModel::END_INVALID) ? '' : 'с ' . date_format(new \DateTime($price->end), "d.m.Y");
					$day = $price->calcValue(\Session::obj()->currency['course']);
					$week = $day - ($price->week_disc * $day) / 100;
					$month = $day - ($price->month_disc * $day) / 100;
					if (!$start && !$end) $start = "Без даты"
					?>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo $start . ' ' . $end; ?></td>
						<td style="text-align: center;"><?php printf("%.2f", $day); ?></td>
						<td style="text-align: center;"><?php printf("%.2f", $week); ?></td>
						<td style="text-align: center;"><?php printf("%.2f", $month); ?></td>
						<td style="text-align: center;"><img src="/static/img/buttons/order.png" width="152" height="30"></td>
					</tr>
					<?php }; ?>
			</table>
		</div>
			<?php }; ?>
	<div style="float:left;">
		<?php
		$apps = $this->realty->getAppartments();
		$rent = array();
		if ($apps->count()) {
			if ($this->realty->type == \RealtyModel::TYPE_VILLA) $hdr = 'Аренда аппартаментов на этой Вилле:'; else $hdr = 'Аренда аппартаменты в этом отеле:';
			foreach ($apps as $app) {
				$prices = $app->getPrices(\PriceModel::TYPE_RENT);
				if ($prices->count()) $rent[] = array(
					'app' => $app, 'price' => $prices[0]->calcValue(\Session::obj()->currency['course'])
				);
			}
		}
		if (!empty($rent)) {
			?>
			<div class="appartlist">
				<div class="appartlistheader"><?php echo $hdr; ?></div>
				<table class="appartlist" border="0" cellpadding="0" cellspacing="0">
					<?php
					foreach ($rent as $a) {
						?>
						<tr>
							<td><a href="#"><?php echo $a['app']->name; ?></a></td>
							<td>от

									<span style="font-size: 1.2em"><?php echo \Session::obj()->currency['sign']; ?>
										&nbsp;<span
											style="color:red;"><b><?php echo $a['price']; ?></b></span></span>
							</td>
							<td><img src="/static/img/buttons/order.png" width="152" height="30"></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		};
		?>
		<?php
		$apps = $this->realty->getAppartments();
		$rent = array();
		if ($apps->count()) {
			if ($this->realty->type == \RealtyModel::TYPE_VILLA) $hdr = 'Продажа аппартаментов на этой Вилле:'; else $hdr = 'Продажа аппартаменты в этом отеле:';
			foreach ($apps as $app) {
				$prices = $app->getPrices(\PriceModel::TYPE_SELL);
				if ($prices->count()) $rent[] = array(
					'app' => $app, 'price' => $prices[0]->calcValue(\Session::obj()->currency['course'])
				);
			}
		}
		if (!empty($rent)) {
			?>
			<div class="appartlist">
				<div class="appartlistheader"><?php echo $hdr; ?></div>
				<table class="appartlist" border="0" cellpadding="0" cellspacing="0">
					<?php
					foreach ($rent as $a) {
						?>
						<tr>
							<td><a href="#"><?php echo $a['app']->name; ?></a></td>
							<td>от

									<span style="font-size: 1.2em"><?php echo \Session::obj()->currency['sign']; ?>
										&nbsp;<span
											style="color:red;"><b><?php echo $a['price']; ?></b></span></span>
							</td>
							<td><?php $this->requestButton('#'); ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		};
		?>
	</div>
		<?php if ($this->realty->gmap) { ?>
	<div style="width:714px;float:left;">
		<h1>Виртуальный осмотр:</h1>
		<iframe width="714" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
				src="<?php echo $this->realty->gmap; ?>"></iframe>
	</div>
		<?php }; ?>
	</div>
	<?php
		$this->end();
		return parent::show();
	}
}
