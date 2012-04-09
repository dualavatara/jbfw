<?php
/**
 * User: zhukov
 * Date: 29.02.12
 * Time: 1:41
 */
namespace Admin;

class StdController extends Controller {
	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $objectName;

	/**
	 * @var \AdminModel
	 */
	protected $model;

	/**
	 * @param string $menu 				Menu key from 'menu' config section
	 * @param string $section			Section key from 'menu' config section
	 * @param string $objectName		Object name to use for template and model.
	 * 									Ex. 'User' for admin/tpl/User/... and
	 * 									admin/model/User.php
	 * @param Admin\Application $app
	 */
	public function __construct($menu, $section, $objectName, \Admin\Application $app) {
		preg_match('/.*\\\\(?<class>[[:alpha:]]+)$/', get_class($this), $m);
		$classname = $m['class'];
		$this->data = $data = array('menu'    => $menu ? $menu : $_SESSION['menu'],
									'section' => $classname);
		$this->objectName = $objectName;

		if ($menu) $_SESSION['menu'] = $menu;

		parent::__construct($app);
		$classname = '\\model\\' . $objectName;
		$this->model = new $classname($this->app['db']);
	}

	public function do_add() {
		$this->data['model'] = $this->model;
		return $this->app['template']->render($this->objectName.'\FormTemplate', $this->data);
	}

	public function do_delete(\Admin\Request $request) {
		$id = $request['id'];

		$this->model->delById($id);

		$url = $this->app->getUrl(strtolower($this->objectName) . '_list');
		return $this->app->redirect($_SESSION['listurl']);
	}

	public function do_edit(\Admin\Request $request) {
		$id = $request['id'];

		if (!$this->model->getById($id)) {
			return $this->app->error404();
		}

		$m = $this->model->getModel();
		$this->data['object'] = $m[0];
		$this->data['model'] = $this->model;

		return $this->app['template']->render($this->objectName.'\FormTemplate', $this->data);
	}

	public function do_list(\Admin\Request $request = null) {
		$this->model->getFiltered($request);

		$class = $this->model->childParamsClass;
		if ($class) {
			$params = new $class($request);
			$_SESSION['urlparams'] = $params->getRequestParams($request);
		}

		$this->data['model'] = $this->model;
		$_SESSION['listurl'] = $_SERVER['REQUEST_URI'];

		return $this->app['template']->render($this->objectName.'\ListTemplate', $this->data);
	}

	public function do_save(\Admin\Request $request) {
		$form = $request['form'];
		//var_dump($request); var_dump($_FILES);return;
		if (count($form['routes'])) {
			$routes = array_keys($form['routes']);
			unset($form['routes']);
		} else $routes = array();

		foreach ($this->model->fields as $field) {
			$field->onSave($form);
		}
		//if there uploaded files with names of model field, store them


		if ($form['id']) $this->model->saveFromForm($form);
		else $this->model->addFromForm($form);

		//$url = $this->app->getUrl(strtolower($this->objectName) . '_list');
		return $this->app->redirect($_SESSION['listurl']);
	}
}
