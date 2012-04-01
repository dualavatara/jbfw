<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 1:18 AM
 */

namespace View;

class IndexView extends BaseView {
	public $template;

	/**
	 * params
	 */
	public $bannersLeft;
	public $realties;
	public $articles;

	/**
	 * @param \View\TemplateView $template
	 * @param $params
	 */
	public function __construct(TemplateView $template, $params = array()) {
		$this->template = $template;
		foreach ($params as $key => $value) {
			$this->$key = $value;
		}
	}

	public function realtyTitleStars($realty) {
		?>
	<h2><?php echo $realty->name; ?>
		<?php
		for ($i = 0; $i < $realty->stars; $i++) {
			?>
			<img src="/static/img/icons/star.png">
			<?php
		}
		?></h2>
	<?php
	}

	public function show() {
		$this->start();
		?>
	<div id="contentlcol">
		<div id="searchpad">
			<form action="/search" method="post" enctype="application/x-www-form-urlencoded" name="form1">
				<div id="catfolds">
					<div class="searchfold selected">
						недвижимость
					</div>
					<div class="searchfold">
						авто
					</div>
					<div class="searchfold">
						отдых
					</div>
				</div>
				<div id="mainsearch">
					<p>
						<label class="lab" for="target">цель</label>
						<select name="target" id="target" class="first">
							<option selected>Аренда</option>
						</select>
					</p>
					<p>
						<label class="labd">c</label>
						<select name="from_day2" id="from_day2" class="firstd">
							<option selected>01</option>
						</select>
						<select name="from_month" id="from_month">
							<option selected>01</option>
						</select>
						<select name="from_year2" id="from_year2">
							<option selected>2012</option>
						</select>
						<span><img src="../static/img/icons/calendar.png" width="24" height="18"></span> <br>
						<label class="labd">по</label>
						<select name="from_day2" id="from_day2" class="firstd">
							<option selected>01</option>
						</select>
						<select name="from_month" id="from_month">
							<option selected>01</option>
						</select>
						<select name="from_year2" id="from_year2">
							<option selected>2012</option>
						</select>
						<img src="../static/img/icons/calendar.png" width="24" height="18">
					</p>
					<p>
						<label class="lab">страна</label>
						<select name="country" id="country" class="first">
							<option selected>Черногория</option>
						</select>
						<br>
						<label class="lab">город</label>
						<select name="country" id="country" class="first">
							<option selected>Цетине</option>
						</select>
					</p>
					<p>
						<label for="type" class="lab">тип</label>
						<select name="type" id="type" class="first">
							<option selected>Апартаменты</option>
						</select>
						<br>
						<label class="lab">S(м&#178;)</label>
						<select name="country" id="country" class="first">
							<option selected>до 100 м&#178;</option>
						</select>
					</p>
				</div>
				<div id="exsearch_fold">
					<div class="fold">
						<div class="foldbg bottom" style="background-color: #898989; ">
							<div class="foldbldark">
								<div class="foldbrdark">
									<div class="foldbsdark">
										<div class="foldi bottom">
											<div class="text" style="width: 218px;padding: 5px 0 ;"><a class="white"
																									   href="#">дополнительные
												параметры поиска</a></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php
		/** @noinspection PhpUndefinedVariableInspection */foreach ($this->bannersLeft as $banner) {
		$size = $this->bannersLeft->getSize($banner->type);
		?>
		<div>
			<a href="<?php echo $banner->link; ?>">
				<img src="/s/<?php echo $banner->image; ?>" width="<?php echo $size->width; ?>"
					 height="<?php echo $size->height; ?>">
			</a>
		</div>
		<?php
	};
		?>
	</div>
	<div id="contentrcol">
		<?php
		foreach ($this->realties as $realty) {
			$this->realtyBlock($realty);
		};
		?>
		<?php
		$artOut = function ($article) {
			?>
			<h2 class="red"><?php echo $article->name;?></h2>
			<img src="/s/<?php echo $article->photo_preview;?>">
			<p><?php echo $article->content_short;?></p>
			<?php
		};
		/** @noinspection PhpUndefinedVariableInspection */if ($this->articles->count()) {
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
	};
		?>
	</div>
	<?php
		$this->end();
		$this->template->setMainContent($this->content);
		return $this->template->show();
	}

	public function realtyBlock($realty) {
		$mainImg = $this->realties->getMainImage($realty->id);
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
		if ($realty->flags->check(\RealtyModel::FLAG_HIT)) {?><img class="badge" src="/static/img/badge/hit.png"><?php
		} else if ($realty->flags->check(\RealtyModel::FLAG_DISCOUNT)) {
			?>
			<img class="badge" src="/static/img/badge/discount.png">
			<?php
		};
					if (isset($mainImg)) {
						?>
						<a href="/s/<?php echo $mainImg->image; ?>"
						   class="lightbox<?php echo 'realty' . $realty->id; ?>"><img
							src="/s/<?php echo $mainImg->thumbnail; ?>" width="125" height="125"></a>
						<?php
					} else {
						?>
						<div class="noimage">Нет изображения</div>
						<?php
					};
					?>
					<a style="line-height:22px;" href="#">подробнее</a>
				</div>
			</div>
			<div class="right">
				<div><?php $this->realtyTitleStars($realty); ?></div>
				<div>
					<table class="properties" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>всего комнат:</td>
							<td><?php echo $realty->rooms ?> (спален: <?php echo $realty->bedrooms ?>)</td>
						</tr>
						<tr>
							<td>курорт:</td>
							<td><?php echo $this->realties->getResort($realty->resort_id)->name; ?></td>
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
					$images = $this->realties->getOtherImages($realty->id);
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
												 style="color:red;"><?php echo $this->realties->getResort($realty->resort_id)->name; ?></span><br>
					<a href="<?php echo $this->realties->getResort($realty->resort_id)->gmaplink; ?>" target="_blank">смотреть
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
		if ($apps->count()) {
			if ($realty->type == \RealtyModel::TYPE_VILLA) $hdr = 'Аппартаменты на этой Вилле:';
			else $hdr = 'Аппартаменты в этом отелле:';
			?>
			<div class="appartlist">
				<div class="appartlistheader"><?php echo $hdr; ?></div>
				<table class="appartlist" border="0" cellpadding="0" cellspacing="0">
					<?php
					foreach ($apps as $app) {
						$prices = $app->getPrices(\PriceModel::TYPE_RENT);
						if ($prices->count()) {
							?>
							<tr>
								<td><a href="#"><?php echo $app->name; ?></a></td>
								<td>от

									<span style="font-size: 1.2em"><?php echo \Session::obj()->currency['sign']; ?>&nbsp;<span
										style="color:red;"><b><?php echo $prices[0]->calcValue(\Session::obj()->currency['course']); ?></b></span></span>
								</td>
								<td><img src="../static/img/buttons/order.png" width="152" height="30"></td>
							</tr>
							<?php
						}
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
