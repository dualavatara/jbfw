<?php

use Admin\Extension\Template\Template;

class SectionsMenuTemplate extends Template {

	public function show($data, $content = null) {
		$config = $this->app->getConfig();

       	$selected = $data['menu'];
		$section  = $data['section'];
		if (!isset($data['menu']) || !isset($data['section'])) {
			throw new Exception('"Menu" or/and "Section" parameter in template is not defined.');
		}

		$sectionTitle = '';
		$sections = array();
		$menuItem = $config->menu[$selected];

		foreach ($menuItem['sections'] as $name => $sectionItem) {
            if($this->app['user']->checkRoute($sectionItem['route'])) {
                $title = $sectionItem['title'];
                if (isset($section) && ($section == $name)) {
                    $sectionTitle = $title;
                    $title = '<span class="menuSelected">' . $title . '</span>';
                }
                $link = $this->getUrl($sectionItem['route'], $sectionItem['params'], true);
                $sections[] = sprintf('<a href="%s">%s</a>', $link, $title);
            }
		}

		print '<div class="breadcrumb">' . $menuItem['title'] . ($sectionTitle ? ' // ' . $sectionTitle : '') . '</div>';
		
		print '<div class="menubar">' . implode(' :: ', $sections) . '</div>';
	}
}