<?php
/**
 * User: dualavatara
 * Date: 4/13/12
 * Time: 5:36 PM
 */
namespace Ctl;

class ArticleCtl extends BaseCtl {
	public function article($id) {
		$tpl = $this->disp->di()->TemplateCtl($this->disp)->main();
		$leftCol = $this->disp->di()->SearchColumnCtl($this->disp)->main();

		$mainView = $this->disp->di()->ArticleView($this);

		$article = $this->disp->di()->ArticleModel();
		$article->get($id)->exec();

		if ($article->count()) $mainView->article = $article[0];

		$mainView->mtArticles = $this->disp->di()->ArticleModel();
		$mainView->mtArticles->getByTags($mainView->article->maintag, 'maintag', $mainView->article->id);

		$mainView->tArticles = $this->disp->di()->ArticleModel();
		$mainView->tArticles->getByTags($mainView->article->tags, 'tags', $mainView->article->id);

		$tpl->setLeftColumn($leftCol->show());
		$tpl->setMainContent($mainView->show());
		return $tpl;
	}

	static public function link($method, $params) {
		switch($method) {
			case 'article' : return '/article/' . $params['id'];
			//case 'index' : return '/realty'. '?' . http_build_query($params);
			default: throw new \NotFoundException();
		}
	}
}
