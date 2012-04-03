<?php
/**
 * User: dualavatara
 * Date: 3/9/12
 * Time: 8:26 PM
 */

namespace View;

class TemplateView extends BaseView {
	public $mainCont;
	public $leftCol;

	public $settings;
	public $bannersHead;
	public $articlesUsefull;
	public $currencies;

	public function setMainContent($content) {
		$this->mainCont = $content;
	}

	public function setLeftColumn($content) {
		$this->leftCol = $content;
	}

	public function show() {
		$this->start();
		?>
	<!DOCTYPE html>
	<!--suppress HtmlUnknownTarget, HtmlUnknownTarget -->
	<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="author"
			  content="<?php /** @noinspection PhpUndefinedVariableInspection */echo $this->settings->getTitle(); ?>">
		<meta name="description" content="<?php echo $this->settings->getDescription(); ?>">
		<meta name="keywords" content="<?php echo $this->settings->getKeywords(); ?>">
		<title><?php echo $this->settings->getTitle(); ?></title>
		<script type="text/javascript" src="/static/js/jquery.js"></script>
		<script type="text/javascript" src="/static/js/lightbox/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false">
		</script>

		<link rel="stylesheet" type="text/css" href="/static/js/lightbox/css/jquery.lightbox-0.5.css" media="screen"/>
		<link rel="stylesheet" href="/static/main.css" type="text/css">
		<link rel="icon" href="/favicon.ico" type="image/x-icon">
		<link rel="shortcut icon" href="/desktop.ico" type="image/x-icon">
	</head>
	<body>

	<div id="container">
		<div id="header">
			<?php $this->header(); ?>
		</div>
		<div id="content">
			<div id="contentlcol">
				<?php echo $this->leftCol; ?>
			</div>
			<div id="contentrcol">
				<?php echo $this->mainCont; ?>
			</div>
		</div>
		<div id="footerstub">&nbsp;</div>
		<div id="footer" class="page">
			<?php $this->footer(); ?>
		</div>
	</div>
	</body>
	</html>
	<?
		$this->end();
		return $this->content;
	}

	public function header() {
		?>
	<div id="top">
		<?php $this->headerTop(); ?>
	</div>
	<div id="image">
		<?php $this->headerImage(); ?>
	</div>
	<div id="menu" style="overflow:visible;">
		<?php $this->headerMenu(); ?>
	</div>
	<div id="banners">
		<?php $this->banners(); ?>
	</div>
	<?php
	}

	public function footer() {
		?>
	<div class="darkgray">
		<div class="lightgray">
			<div class="lcol">
				<div class="footerblock">
					<h1>Отдых в Черногории</h1>
					<ul>
						<li><a href="#">Testlink</a></li>
						<li><a href="#">Testlink</a></li>
						<li><a href="#">Testlink</a></li>
						<li><a href="#">Testlink</a></li>
					</ul>
				</div>
			</div>
			<div class="rcol">
				<div class="footerblock">
					<h1>Полезная информация</h1>
					<ul>
						<?php
						//var_dump($this->articlesUsefull->data);
						/** @noinspection PhpUndefinedVariableInspection */foreach ($this->articlesUsefull as $article) {
						?>
						<li><a href="/article"><?php echo $article->name; ?></a></li>
						<?php
					};
						?>
					</ul>
				</div>
			</div>
			<div class="rcol" id="contacts">
				<div class="footerblock">
					<h1>Свяжитесь с нами</h1>
					<?php
					if ($this->settings->getAddress()) {
						?><p><?php
							echo $this->settings->getAddress();
							?></p>
						<?php
					};
					?>
					<?php
					if ($this->settings->getPhone1() | $this->settings->getPhone2() | $this->settings->getFax()) {
						?>
						<div class="record">
							<div class="iconcol"><img src="/static/img/icons/phone.png"></div>
							<div>
								<?php
								if ($this->settings->getPhone1()) {
									?>
									<?php echo $this->settings->getPhone1(); ?> - в Москве<br/>
									<?php
								};
								?>
								<?php
								if ($this->settings->getPhone1()) {
									?>
									<?php echo $this->settings->getPhone2(); ?> - в Черногории<br/>
									<?php
								};
								?>
								<?php
								if ($this->settings->getFax()) {
									?>
									<?php echo $this->settings->getFax(); ?> - факс<br/>
									<?php
								};
								?>
							</div>
						</div>
						<?php
					}
					?>

					<?php
					if ($this->settings->getSkype()) {
						?>
						<div class="record">
							<div class="iconcol"><a href="skype:<?php echo $this->settings->getSkype(); ?>?add"><img
								src="/static/img/icons/skype.png"></a></div>
							<div class="middle">
								<?php
								if ($this->settings->getSkype()) {
									?>
									<a href="skype:<?php echo $this->settings->getSkype(); ?>?add"> <?php echo $this->settings->getSkype(); ?></a>
									<?php
								};
								?>
							</div>
						</div>
						<?php
					}
					?>

					<?php
					if ($this->settings->getEmail()) {
						?>
						<div class="record">
							<div class="iconcol"><a href="mailto:<?php echo $this->settings->getEmail(); ?>"><img
								src="/static/img/icons/email.png"></a></div>
							<div class="middle">
								<?php
								if ($this->settings->getEmail()) {
									?>
									<a href="mailto:<?php echo $this->settings->getEmail(); ?>"> <?php echo $this->settings->getEmail(); ?></a>
									<?php
								};
								?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div id="seo">
		<p><?php echo $this->settings->getSEOText(); ?></p>
	</div>
	<div id="countersbar">
		<div id="counter">&copy; 2012. Все права защищены.</div>
		<div id="networks"><!--networks--></div>
	</div>
	<?php
	}

	public function banners() {
		$i = 0;
		/** @noinspection PhpUndefinedVariableInspection */
		foreach ($this->bannersHead as $banner) {
			$i++;
			?>
		<div class="bannercol">
			<a href="<?php echo $banner->link; ?>"><img
				src="<?php echo \Ctl\StaticCtl::link('get', array('key' => $banner->image)); ?>"
				class="image"></a>
		</div>
		<?php
		}
		;
	}

	public function headerImage() {
		?>
	<div id="logo"><a href="/"><img src="/static/img/logo.png" width="290" height="131"></a></div>
	<img src="/static/img/0.png" width="966" height="1">
	<?php
	}

	public function headerMenu() {
		?>
	<script type="text/javascript">
		$(function () {
			$('.menuitem').each(function () {
				$(this).mouseout(function () {
					$(this).removeClass('selected');
				})
			});
			$('.menuitem').mouseover(function () {
				$(this).addClass('selected');
			});
		});
	</script>

	<div class="menuitem">Аренда авто</div>
	<div class="menuitem">Аренда жилья</div>
	<div class="menuitem">Продажа авто</div>
	<a style="text-decoration: none;" href="<?php echo \Ctl\RealtyCtl::link('index', array());?>">
		<div class="menuitem">Недвижимость</div>
	</a>
	<div class="menuitem">Услуги</div>
	<div class="menuitem">Советы</div>
	<?php
	}

	public function headerTop() {
		$curCur = \Session::obj()->currency;
		?>
	<div id="headerfolds">
		<div>
			<div class="hfold selected">RU</div>
		</div>
		<div>
			<?php
			foreach ($this->currencies as $currency) {
				if ($curCur['id'] != $currency->id) {
					?>
					<div class="hfold">
						<i><a class="hiddenlink"
							  href="<?php echo \Ctl\IndexCtl::link('setCurrency', array('value' => $currency->id));?>"><?php echo $currency->name; ?></a></i>
					</div>
					<?php
				} else {
					?>
					<div class="hfold selected"><i><?php echo $currency->name; ?></i></div>
					<?php
				}
			}; ?>
		</div>
	</div>
	<div id="headerphone">
		<div style="height: 5em;float:left;width: 7em;">
			<div class="label grey">В РОССИИ:</div>
			<div class="label grey">В ЧЕРНОГОРИИ:</div>
		</div>
		<div style="height: 5em;width: 17em;float:left;">
			<div class="title black"><?php echo $this->settings->getPhone1(); ?></div>
			<div class="title black"><?php echo $this->settings->getPhone2(); ?></div>
		</div>
	</div>
	<div id="headerlogin">&nbsp;</div>
	<?
	}
}
