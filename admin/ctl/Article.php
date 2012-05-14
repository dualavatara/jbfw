<?php
namespace ctl;

require_once 'admin/lib/StdController.php';

class Article extends \Admin\StdController {
	public function __construct(\Admin\Application $app, \Admin\Route $route) {
		parent::__construct($route->getMenu(), 'article_list', 'Article', $app);
	}

	public function do_save(\Admin\Request $request) {
		$form = $request['form'];
		$form['maintag']=explode(',', $form['maintag']);
		array_walk($form['maintag'], function(&$val, $key) {
			$val = trim($val);
		});
		$form['maintag'] = serialize($form['maintag']);
		$form['tags']=explode(',', $form['tags']);
		array_walk($form['tags'], function(&$val, $key) {
			$val = trim($val);
		});
		$form['tags'] = serialize($form['tags']);

		return parent::do_save(array('form' => $form));
	}
}