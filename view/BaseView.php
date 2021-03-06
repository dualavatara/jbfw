<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 1:47 AM
 */

namespace View;

class BaseView implements IView {
	public $content;
	public $sort = "ord";
	public $sortDir = -1;
	public $page = 1;

	public $ctl;

	//const PAGE_SIZE = 5;

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

	public function columnHeader($header, $align = 'center', $color = '') {
		?>
	<div class="title black" style="line-height:64px;<?php echo $color; ?>">
		<h1 class="smalltitle" style="text-align:<?php echo $align; ?>"><?php echo $header; ?></h1>
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

	public function blockMainImg($id, $img, $profileLink, $isHit, $isDiscount, $onClick, $showLink, $field, $w, $h) {
		?>
	<div class="pic_cont" style="width: <?php echo $w+2; ?>px">

		<script type="text/javascript">
			$(function () {
				// lightbox
				$('a.lightbox<?php echo $id; ?>').lightBox(
					{
						txtImage:'Фото',
						txtOf:'из'
					}
				); // Select all links with lightbox class
				$('a.lightbox<?php echo $id; ?>').imgPreview({
					containerID:'imgPreviewWithStyles',
					imgCSS:{
						// Limit preview size:
						height:200
					},
				});
			});
		</script>
		<?php
		if ($isHit) {
			?><img class="badge" src="/static/img/badge/hit.png"><?php
		} else if ($isDiscount) {
			?>
			<img class="badge" src="/static/img/badge/discount.png">
			<?php
		};
		if (isset($img)) {
			?>
			<a href="<?php echo $profileLink; ?>"  onclick="<?php echo $onClick; ?>"><img
				src="<?php echo \Ctl\StaticCtl::link('get', array('key' => $img->{$field})); ?>"
				width="<?php echo $h; ?>"
				height="<?php echo $w; ?>"
				style="border: solid 1px #cfcfcf;"
				alt="<?php echo $img->alt; ?>"></a>
			<?php
		} else {
			?>
			<div class="noimage" style="width: <?php echo $w+2; ?>px; height: <?php echo $h+2; ?>px">
				<div style="padding-top: <?php echo $h/2 -6; ?>px">Нет изображения</div></div>
			<?php
		};
		if ($showLink) {
		?>
		<a style="line-height:22px;"
		   href="<?php echo $profileLink; ?>" onclick="<?php echo $onClick; ?>">подробнее</a>
			<?php }; ?>
	</div>
	<?php
	}

	public function blockOtherImg($id, $images, $num = 6) {
		?>
	<script type="text/javascript">
		$(function () {
			// lightbox
			$('a.lightbox<?php echo $id; ?>').lightBox(
				{
					txtImage:'Фото',
					txtOf:'из'
				}
			); // Select all links with lightbox class
			$('a.lightbox<?php echo $id; ?>').imgPreview({
				containerID:'imgPreviewWithStyles',
				imgCSS:{
					// Limit preview size:
					height:200
				},
			});
		});
	</script>
		<?php
		$i = 0;
		foreach ($images as $image) {
			$i++;
			if ($i > $num) break;
			?>
		<div class="thumbnail">
			<a href="/s/<?php echo $image->image; ?>"
			   class="lightbox<?php echo $id; ?>">
				<img src="/s/<?php echo $image->thumbnail50; ?>" width="50" height="50" alt="<?php echo $image->alt; ?>">
			</a>
		</div>
		<?php
		}
		;
	}

	public function blockProperties($map) {
		?>
	<table class="properties" border="0" cellpadding="0" cellspacing="0" width="100%">
		<?php
		foreach ($map as $key => $val) {
			?>
			<tr>
				<td><?php echo $key; ?>:</td>
				<td><?php echo $val; ?></td>
			</tr>
			<?php }; ?>
	</table>
	<?php
	}

	public function realtyBlock($realty, $withAppts = true) {
		$mainImg = $realty->getMainImage();
		?>
	<div class="itemblock">
		<div class="left">
			<div class="left">
				<?php
				$this->blockMainImg('realty' . $realty->id,
					$mainImg,
					\Ctl\RealtyCtl::link('profile', array('id' => $realty->id)),
					$realty->flags->check(\RealtyModel::FLAG_HIT),
					$realty->flags->check(\RealtyModel::FLAG_DISCOUNT),
					'',
					true,
					'thumbnail125',
					125,
					125
				);
				?>
			</div>
			<div class="right">
				<div><?php $this->titleStars($realty->name, $realty->stars); ?></div>
				<div style="margin-left: 13.5em;padding-right: 1em;">
					<?php
					$props = array(
						'всего комнат' => $realty->rooms . ' (спален: ' . $realty->bedrooms . ')',
						'курорт' => $realty->getResort()->name,
						'особенности' => $realty->features,
						'этаж' => $realty->floor . '/' . $realty->total_floors
					);
					$this->blockProperties($props);
					?>
				</div>
				<div><?php
					$this->blockOtherImg('realty' . $realty->id, $realty->getOtherImages());
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
				if (!empty($price)) {
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
						if ($type == \PriceModel::TYPE_RENT) {
							?>
							<?php $this->orderButton('#'); ?>
							<?php
						} else {
							$this->requestButton('#');
						}
						;
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
				$hdr = 'Аренда аппартаментов:';
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
								<td><?php $this->orderButton('#'); ?></td>
							</tr>
							<?php
						};
						?>
					</table>
				</div>
				<?php
			}
			;
		};
		?>
	</div>
	<?php
	}

	public function carsBlock($car, $left = false) {
		$mainImg = $car->getMainImage();
		$orderLink = '/carorder/' . $car->id;
		$fromDate = $_REQUEST['auto']['place_from']['date'];
		$toDate = $_REQUEST['auto']['place_to']['date'];
		$order=$_REQUEST['auto'];
		if (($order['place_to']['hour']>12)
                                                || ($order['place_to']['hour'] == 12 && $order['place_to']['minute'] > 0)) {
                                                $td = new \DateTime($toDate);
                                                $td->add(new \DateInterval('P1D'));
                                                $toDate = $td->format('d.m.Y');
                                        }
		?>
	<div class="itemblock" style="width: 29.2em; height: 15em;
float: left;<?php if($left) echo 'margin-right:0.3em'; ?>">
		<div style="width: 11em;padding: 1em 0.5em 0.5em 1em;float:left;">
			<?php
			$link = \Ctl\CarCtl::link('profile', array('id' => $car->id));

			$this->blockMainImg('cars' . $car->id, $mainImg,
				'javascript:void(0)',
				$car->flags->check(\CarModel::FLAG_HIT),
				$car->flags->check(\CarModel::FLAG_DESCOUNT),
				"openPopup('".$orderLink."', {id:'step1popup', width:450, height:600, title:'Аренда авто. Шаг 1.'});",
				true,
				'thumbnail125', 125,125
			);
			?>
		</div>
		<div id="window_block2" style="display:none;">

		</div>
		<div style="margin-left: 13em">
			<div><h2><?php echo $car->name; ?></h2></div>
			<div>
				<?php
				$props = array(
					'кол-во пассажиров' => $car->seats,
					'кол-во дверей' => $car->doors,
					'коробка передач' => $car->flags->check(\CarModel::FLAG_AUTOMAT) ? 'АКП' : 'МКП',
					'кондиционер' => $car->flags->check(\CarModel::FLAG_CONDITIONER) ? 'да' : 'нет',
				);
				$this->blockProperties($props);
				?>
			</div>
			<div>
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
						if ($fromDate && $toDate){
							$text = '<span
						style="color:red;"><b>' . $car->calcPricesDated($fromDate, $toDate, \Session::obj()->currency['course'], $type) . '</b></span> ' . \Session::obj()->currency['sign'] ;
							$text .= '<div style="font-size: 0.6em; color: #555;text-align: right;">с '.$fromDate.' по '.$toDate.' </div>';
						}
						else {
							$e = new \DateTime($price[0]->end);
							$td = 'цена действительна';
                            $td .= $price[0]->flags->check(\PriceModel::END_INVALID) ? '' : ' до '. $e->format('m.Y');
                            $pval = $price[0]->calcValue(\Session::obj()->currency['course']);
                            $disc = $price[0]->week_disc > $price[0]->month_disc ? $price[0]->week_disc/100.0 : $price[0]->month_disc/100.0;
                            $disc = ceil($pval * $disc);
                            $pval -= $disc;
							$text = 'от <span
						style="color:red;"><b>' . $pval . '</b></span> ' . \Session::obj()->currency['sign'] . ' в сутки';
							$text .= '<div style="font-size: 0.6em; color: #555;text-align: right;">'.$td.'</div>';
						}
						?>
					<span style="font-size: 1.6em; "><?php echo $text;?></span>
					<?php } else echo "&nbsp;"; ?></div>
				<div><?php
					if ($type) {
						if ($type == \PriceModel::TYPE_RENT) {

							?>
							<?php $this->orderButton('javascript:void(0);', "openPopup('".$orderLink."', {id:'step1popup', width:450, height:600, title:'Аренда авто. Шаг 1.'});") ?>
							<?php
						} else {
							$this->requestButton('#');
						}
						;
					};
					?></div>
			</div>
		</div>
	</div>
	<?php
	}

	public function navBarTab($name, $href, $selected, $linkSelected = false) {
		if (!$selected) {
			?>
		<span class="hfold">
			<a class="hiddenlink" href="<?php echo $href; ?>"><?php echo $name; ?></a>
		</span>
		<?php
		} else if ($linkSelected) {
			?>
		<span class="hfold selected">
			<a class="hiddenlink" href="<?php echo $href; ?>"><?php echo $name; ?></a>
		</span>
		<?php
		} else {
			?>
		<span class="hfold selected"><?php echo $name; ?></span>
		<?php
		}

	}

	public function navBarSortTab($name, $sort, $defDir) {
		if ($sort == $this->sort) $dir = -$this->sortDir; else $dir = $defDir;
		$s = $dir > 0 ? '<img src="/static/img/icons/sort_up.png">' : '<img src="/static/img/icons/sort_down.png">';

		$curReq = $_REQUEST;
		$curReq['sort'] = $sort;
		$curReq['dir'] = $dir;
		$this->navBarTab($s . "&nbsp;" . $name, $this->ctl->link('index', $curReq), ($this->sort == $sort), true);
	}

	public function navBarPager($npages, $num = 0) {
		$curReq = $_REQUEST;

		if ($num) $start = $this->page - floor(floatval($num) / 2);
		else $start = 1;
		if ($start < 1) $start = 1;

		$end = $num ? $start + $num - 1 : $npages;
		if ($end > $npages) {
			$end = $npages;
			$start = $npages - $num + 1 > 0 ? $npages - $num + 1: $start;
		}

		if ($this->page > 1) {
			$curReq['page'] = $this->page - 1;
			echo '<span><a style="text-decoration: none; background-color: #CC0000;color: white;padding: 0 0.2em" href="' . $this->ctl->link('index', $curReq) . '">&lt;</a></span>';
		}
		for ($i = $start; $i <= $end; $i++) {
			$curReq['page'] = $i;
			$this->navBarTab($i, $this->ctl->link('index', $curReq), ($this->page == $i));
		}

		if ($this->page < $npages) {
			$curReq['page'] = $this->page + 1;
			echo '<span class="hfold"><a style="text-decoration: none; background-color: #CC0000;color: white;padding: 0 0.2em" href="' . $this->ctl->link('index', $curReq) . '">&gt;</a></span>';
		}
		$curReq['page'] = 'all';
		$this->navBarTab('все', $this->ctl->link('index', $curReq), ($this->page == 'all'));
	}

	public function navBar($npages) {

		$curCur = \Session::obj()->currency;
		?>
		<div style="float:left;width:60em;">
	<table class="navbar" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
				<span class="hfold"><b>сортировка:</b></span>
				<?php $this->navBarSortTab('популярность', 'ord', -1); ?>
				<?php $this->navBarSortTab('цена', 'price', 1); ?>
			</td>
			<td><span class="hfold"><b>валюты:</b></span>
				<?php
				foreach ($this->currencies as $currency) {
					$this->navBarTab($currency->name, \Ctl\IndexCtl::link('setCurrency', array('value' => $currency->id)), ($curCur['id'] == $currency->id));
				}; ?>
			</td>
			<td><span class="hfold"><b>страницы:</b></span>
				<?php $this->navBarPager($npages, 5); ?>
			</td>
		</tr>
	</table>
		</div>
	<?php
	}
	
	function articlesPreviewBlock($articles) {
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
		if ($articles->count()) {
			?>
		<div id="article_block" style="float: left;width: 60em;">
			<div class="alcol frame"><?php $artOut($articles[0]);?></div>
			<?php
			if ($articles->count() >= 3) {
				?>
				<div class="arcol">
					<div class="alcol frame"><?php $artOut($articles[1]);?></div>
					<div class="arcol frame"><?php $artOut($articles[2]);?></div>
				</div>
				<?php
			} else if ($articles->count() == 2) {
				?>
				<div class="arcol frame"><?php $artOut($articles[1]);?></div>
				<?php
			};
			?>
		</div>

		<?php
		}
		;
	}
}
