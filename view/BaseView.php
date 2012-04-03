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

	public function orderButton($href, $onClick = '') {
		?>
	<a href="<? echo $href; ?>" onclick="<? echo $onClick; ?>"><img src="/static/img/buttons/order.png"></a>
	<?php
	}

	public function phoneBlock() {
		?>
	<div style="float: right;width: 18em;position: relative;">
		<img src="/static/img/icons/phone.png" width="40" height="40"
			 style="position: absolute;top: 1.5em;">

		<h3 style="margin-left: 3em;margin-bottom:0.2em;">Остались вопросы?</h3>

		<div
			style="margin-left: 2em;text-align: center;border: none;border-radius: 1em;padding: 1em;padding: 1px;background-color: #cccccc">
			<div
				style="text-align: center;border: solid #ffffff 2px;border-radius: 1em;padding: 0.6em;background-color: #60a819">
				<h2 style="color: white;margin:0;"><?php echo \Settings::obj()->get()->getPhone1(); ?></h2>
			</div>
		</div>
	</div>
	<?php
	}

	public function realtyBlock($realty, $withAppts = true) {
		$mainImg = $realty->getMainImage();
		?>
	<div class="itemblock">
		<div class="left">
			<div class="left">
				<div class="pic_cont">
					<script type="text/javascript">
						$(function () {
							// This, or...
							$('a.lightbox<?php echo 'realty' . $realty->id; ?>').lightBox(
								{
									txtImage:'Фото',
									txtOf:'из'
								}
							); // Select all links with lightbox class
							// This, or...
						});
					</script>
					<?php
					if ($realty->flags->check(\RealtyModel::FLAG_HIT)) {
						?><img class="badge" src="/static/img/badge/hit.png"><?php
					} else if ($realty->flags->check(\RealtyModel::FLAG_DISCOUNT)) {
						?>
						<img class="badge" src="/static/img/badge/discount.png">
						<?php
					};
					if (isset($mainImg)) {
						?>
						<a href="<?php echo \Ctl\StaticCtl::link('get', array('key' => $mainImg->image)); ?>"
						   class="lightbox<?php echo 'realty' . $realty->id; ?>"><img
							src="<?php echo \Ctl\StaticCtl::link('get', array('key' => $mainImg->thumbnail)); ?>"
							width="125" height="125"></a>
						<?php
					} else {
						?>
						<div class="noimage">Нет изображения</div>
						<?php
					};
					?>
					<a style="line-height:22px;"
					   href="<?php echo \Ctl\RealtyCtl::link('profile', array('id' => $realty->id)); ?>">подробнее</a>
				</div>
			</div>
			<div class="right">
				<div><?php $this->titleStars($realty->name, $realty->stars); ?></div>
				<div>
					<table class="properties" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>всего комнат:</td>
							<td><?php echo $realty->rooms ?> (спален: <?php echo $realty->bedrooms ?>)</td>
						</tr>
						<tr>
							<td>курорт:</td>
							<td><?php echo $realty->getResort()->name; ?></td>
						</tr>
						<tr>
							<td>особенности:</td>
							<td><?php echo $realty->features ?></td>
						</tr>
						<tr>
							<td>этаж:</td>
							<td><?php echo $realty->floor ?>/<?php echo $realty->total_floors ?></td>
						</tr>
					</table>
				</div>
				<div><?php
					$images = $realty->getOtherImages();
					$i = 0;
					foreach ($images as $image) {
						$i++;
						if ($i > 6) break;
						?>
						<div class="thumbnail">
							<a href="/s/<?php echo $image->image; ?>"
							   class="lightbox<?php echo 'realty' . $realty->id; ?>">
								<img src="/s/<?php echo $image->thumbnail; ?>" width="50" height="50">
							</a>
						</div>
						<?php
					};
					?>
				</div>
			</div>
		</div>
		<div class="right">
			<div class="outer_pad">
				<?php
				$price = $realty->getAppartmentPrices();
				$priceValue = '';
				$type = '';
				if (!empty($price)){
					$priceValue = $price[0]->calcValue(\Session::obj()->currency['course']);
					if ($price[0]->type == \PriceModel::TYPE_SELL) $type = \PriceModel::TYPE_SELL;
					if ($price[0]->type == \PriceModel::TYPE_RENT) $type = \PriceModel::TYPE_RENT;
				}
				$rent = true;
				//var_dump($price);
				?>
				<!--<div>оценка <span class="rating_num"><b>9,0</b></span> (отзывы <a href"#">319</a>)</div>-->
				<div>&nbsp;</div>
				<div class="white">курорт: <span class="rating_num"
												 style="color:red;"><?php echo $realty->getResort()->name; ?></span><br>
					<a href="<?php echo $realty->getResort()->gmaplink; ?>" target="_blank">смотреть
						на карте</a></div>
				<div class="rating_num">
					<?php if ($priceValue) { ?>
					<?php echo \Session::obj()->currency['sign']; ?> <span
						style="color:red;"><b><?php echo $price[0]->calcValue(\Session::obj()->currency['course']);?></b></span>
					<?php } else echo "&nbsp;"; ?>
				</div>
				<div style="padding:0px;">
					<?php
					if ($type) {
					if ($type == \PriceModel::TYPE_RENT) {?>
						<?php $this->orderButton('#'); ?>
						<?php } else { $this->requestButton('#'); };
					};
				?>
				</div>
			</div>
		</div>
		<?php
		if ($withAppts) {
			$apps = $realty->getAppartments();
			$rent = array();
			if ($apps->count()) {
				if ($realty->type == \RealtyModel::TYPE_VILLA) $hdr = 'Аренда аппартаментов на этой Вилле:'; else $hdr = 'Аренда аппартаменты в этом отеле:';
				foreach ($apps as $app) {
					$prices = $app->getPrices(\PriceModel::TYPE_RENT);
					if ($prices->count()) $rent[] = array(
						'app' => $app,
						'price' => $prices[0]->calcValue(\Session::obj()->currency['course'])
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
								<td><?php $this->orderButton('#'); ?></td>
							</tr>
							<?php
						};
						?>
					</table>
				</div>
				<?php
			};
		};
		?>
	</div>
	<?php
	}
}
