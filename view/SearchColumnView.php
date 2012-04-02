<?php
/**
 * User: dualavatara
 * Date: 4/1/12
 * Time: 8:27 AM
 */

namespace View;

class SearchColumnView extends BaseView{
	public function show() {
		$this->start();
		$this->columnHeader('Искать на сайте:', 'left');
		?>
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
				<img src="<?php echo \Ctl\StaticCtl::link('get', array('key'=>$banner->image)); ?>" width="<?php echo $size->width; ?>"
					 height="<?php echo $size->height; ?>">
			</a>
		</div>
		<?php
		};
		$this->end();
		return $this->content;
	}

}
