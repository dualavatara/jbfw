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

	public function realtyBlock($realty) {
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
				//var_dump($price);
				?>
				<!--<div>оценка <span class="rating_num"><b>9,0</b></span> (отзывы <a href"#">319</a>)</div>-->
				<div>&nbsp;</div>
				<div class="white">курорт: <span class="rating_num"
												 style="color:red;"><?php echo $realty->getResort()->name; ?></span><br>
					<a href="<?php echo $realty->getResort()->gmaplink; ?>" target="_blank">смотреть
						на карте</a></div>
				<div class="rating_num">
					<?php if (!empty($price) && $price[0]->calcValue(\Session::obj()->currency['course'])) { ?>
					<?php echo \Session::obj()->currency['sign']; ?> <span
						style="color:red;"><b><?php echo $price[0]->calcValue(\Session::obj()->currency['course']);?></b></span>
					<?php } else echo "&nbsp;"; ?>
				</div>
				<div style="padding:0px;"><img src="../static/img/buttons/order.png" width="152" height="30">
				</div>
			</div>
		</div>
		<?php
		$apps = $realty->getAppartments();
		$rent = array();
		if ($apps->count()) {
			if ($realty->type == \RealtyModel::TYPE_VILLA) $hdr = 'Аренда аппартаментов на этой Вилле:'; else $hdr = 'Аренда аппартаменты в этом отеле:';
			foreach ($apps as $app) {
				$prices = $app->getPrices(\PriceModel::TYPE_RENT);
				if ($prices->count()) $rent[] = array('app' => $app, 'price' => $prices[0]->calcValue(\Session::obj()->currency['course']));
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
	</div>
	<?php
	}
}
