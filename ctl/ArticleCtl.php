<?php
/**
 * User: dualavatara
 * Date: 4/13/12
 * Time: 5:36 PM
 */
namespace Ctl;

class ArticleCtl extends BaseCtl {
	public function article($id) {

	}

	static public function link($method, $params) {
		switch($method) {
			case 'article' : return '/article/' . $params['id'];
			//case 'index' : return '/realty'. '?' . http_build_query($params);
			default: throw new \NotFoundException();
		}
	}
}
