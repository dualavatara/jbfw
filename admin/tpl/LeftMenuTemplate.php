<?php

use Admin\Extension\Template\Template;

class LeftMenuTemplate extends Template {

	public function show($data, $content = null) {
		$config = $this->app->getConfig();

		$html = "<ul>\n";
		$allowed = $this->app['user']->getRoutes();
		foreach ($config->menu as $name => $item) {
			$title = $item['title'];
			if (isset($data['menu']) && ($data['menu'] == $name)) {
				$title = '<span class="menuSelected">' . $title . '</span>';
			}


			$item['sections'] = array_filter($item['sections'], function($val) use ($allowed) {
				foreach ($allowed as $k => $v) {
					if ($val['route'] == $v) return true;
				}
				return false;
			});

			//	array_intersect_key($item['sections'], array_flip($allowed));

			if (count($item['sections'])) {
				$section = array_shift($item['sections']);
				$url = isset($section['params']) ? $this->getUrl($section['route'], $section['params'], true) : $this->getUrl($section['route'], array(), true);
				$html .= '<li><a href="' . $url . '">' . $title . '</a></li>';
			}
		}
		$html .= "</ul>\n";

		print $html;
	}
}