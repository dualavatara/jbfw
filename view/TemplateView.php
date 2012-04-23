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
	/**
	 * @var NavigationModel
	 */
	public $navigation;

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
		<!--<script type="text/javascript" src="/static/js/jquery-ui.js"></script>-->
		<script type="text/javascript" src="/static/js/jquery-ui-1.8.19.custom.min.js"></script>
		<script type="text/javascript" src="/static/js/jquery.ui.datepicker-ru.js"></script>
		<script type="text/javascript" src="/static/js/imgpreview.full.jquery.js"></script>
		<script type="text/javascript" src="/static/js/lightbox/jquery.lightbox-0.5.min.js"></script>
		<script type="text/javascript" src="/static/js/jquery-window-5.03/jquery.window.min.js"></script>
		<script type="text/javascript" src="/static/js/main.js"></script>
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false">
		</script>

		<link rel="stylesheet" type="text/css" href="/static/js/lightbox/css/jquery.lightbox-0.5.css" media="screen"/>
		<link rel="stylesheet" type="text/css" href="/static/jquery-ui-1.8.19.custom.css" media="screen"/>
		<link rel="stylesheet" type="text/css" href="/static/js/jquery-window-5.03/css/jquery.window.css"
			  media="screen"/>
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
					<?php
					$parent = $this->navigation->byId(\NavigationModel::ID_FOOTERLEFT);
					if ($parent) {
						$children = $this->navigation->byParentId($parent->id);
						?>
						<h1><?php echo $parent->name; ?></h1>
						<ul>
							<?php
							foreach ($children as $child) {
								$blank = $child->flags->check(\NavigationModel::FLAG_BLANK) ? ' target="_blank"': '';
								?>
								<li><a href="<?php echo $child->link; ?>" <?php echo $blank; ?>><?php echo $child->name; ?></a></li>
								<?php }; ?>
						</ul>
						<?php }; ?>
				</div>
			</div>
			<div class="rcol">
				<div class="footerblock">
					<?php
					$parent = $this->navigation->byId(\NavigationModel::ID_FOOTERRIGHT);
					if ($parent) {
						$children = $this->navigation->byParentId($parent->id);
						?>
						<h1><?php echo $parent->name; ?></h1>
						<ul>
							<?php
							foreach ($children as $child) {
								$blank = $child->flags->check(\NavigationModel::FLAG_BLANK) ? ' target="_blank"': '';
								?>
								<li><a href="<?php echo $child->link; ?>" <?php echo $blank; ?>><?php echo $child->name; ?></a></li>
								<?php }; ?>
						</ul>
						<?php }; ?>
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
			$target = '';
			if ($banner->flags->check(\BannerModel::FLAG_NEWWINDOW)) $target = ' target="_blank"';
			?>
		<div class="bannercol">
			<a href="<?php echo $banner->link; ?>" <?php echo $target; ?>><img
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
			$('.menuitem,.submenuitem').each(function () {
				$(this).mouseout(function () {
					$(this).removeClass('selected');
				})
			});
			$('.menuitem,.submenuitem').mouseover(function () {
				$(this).addClass('selected');
			});
		});
		function showSubmenu(id) {
			parent = $('#menu' + id);
			submenu = $('#submenu' + id);
			var eo = parent.offset();
			eo.top += parent.innerHeight();
			//eo["min-width"] = $(this).width()+$(this).outerHeight();
			eo.visibility = 'visible';
			submenu.css(eo);
			parent.addClass('selected');
		}
		function hideSubmenu(id) {
			parent = $('#menu' + id);
			submenu = $('#submenu' + id);
			submenu.css('visibility', 'hidden');
			parent.removeClass('selected');
		}
	</script>

	<?php
		$parent = $this->navigation->byId(\NavigationModel::ID_MENU);
		if ($parent) {
			$children = $this->navigation->byParentId($parent->id);

			foreach ($children as $child) {
				$blank = $child->flags->check(\NavigationModel::FLAG_BLANK) ? ' target="_blank"': '';
				?>
			<a
			   style="text-decoration: none;"
			   href="<?php echo $child->link; ?>"
				<?php echo $blank; ?>
			   onmouseover="showSubmenu('<?php echo $child->id; ?>');"
				onmouseout="hideSubmenu('<?php echo $child->id; ?>');"
				>
				<div class="menuitem" id="menu<?php echo $child->id; ?>"><?php echo $child->name; ?></div>
			</a>
				<div id="submenu<?php echo $child->id; ?>"
					 style="position: absolute; padding: 1em 0em; background-color: #ffcf00; visibility: hidden;z-index: 99999;
					 box-shadow: 3px 3px 10px rgba(0,0,0,0.5);
    -moz-box-shadow: 3px 3px 3px rgba(0,0,0,0.5);
    -webkit-box-shadow: 3px 3px 3px rgba(0,0,0,0.5);"
					 onmouseover="showSubmenu('<?php echo $child->id; ?>');"
					 onmouseout="hideSubmenu('<?php echo $child->id; ?>');"
					>
				<?php
				$subs = $this->navigation->byParentId($child->id);
				foreach($subs as $sub) {
					$blank = $sub->flags->check(\NavigationModel::FLAG_BLANK) ? ' target="_blank"': '';
					?>
					<a style="text-decoration: none;" href="<?php echo $sub->link; ?>" <?php echo $blank; ?>>
						<div class="submenuitem"><?php echo $sub->name; ?></div>
					</a>
					<?php
				} ?>
				</div>
				<?php
			};
		}; ?>
	<?php
	}

	public function headerTop() {
		$curCur = \Session::obj()->currency;
		?>
	<div id="headerfolds">
		<div>
			<div class="hfold selected">RU</div>
		</div>
		<div style="width: 2em;">
		&nbsp;
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
	<div id="headerlogin">
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
	<?
	}
}
