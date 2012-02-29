<?php
namespace ctl;

class Auth extends \Admin\Controller {
	
	public function do_login(\Admin\Request $request) {
		if ($this->app['user']->isAuthenticated()) {
			return $this->redirectHome();
		}
		
		$form = isset($request['form']) ? $request['form'] : array();
				
		if ($this->app['user']->authenticate($form['login'], $form['password'])) {
			// Redirect back user to page that has been requested before user redirection to login
			if (null != ($referrer = $this->app['session']->read('referrer'))) {
				return $this->app->redirect($referrer);
			}
			
			return $this->redirectHome();
		}
		
		return $this->app['template']->render('LoginTemplate', array('form' => $form));
	}
	
	public function do_logout() {
		$this->app['user']->logout();
		return $this->redirectHome();
	}

	private function redirectHome() {
		$url = $this->app->getUrl('home');
		return $this->app->redirect($url);
	}
}