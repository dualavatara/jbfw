<?
require_once('lib/dicontainer.lib.php');
require_once('model/sending.model.php');
/*
 Новые - пользователь создан не более NEW_USER_NUM_DAYS назад.
 Пассивные - ни одного захода за PASSIVE_NUM_DAYS
 Активные - заход за PASSIVE_NUM_DAYS
 Платящие - оплата за PASSIVE_NUM_DAYS
 Неплатящие - оплата за PASSIVE_NUM_DAYS
 Платящие активные, неплатящие активные,
платящие неактивные и неплатящие неактивные комбинацией четырех предыдущих.
 */

interface IEmailSelector {

	public  function get($consumer_key, $pay_option);

}

class ApplicationAccountSelect implements IEmailSelector {

	/**
	 * @var DIContainer
	 */
	protected  $di;


	public function __construct(){
		$this->di = new DIContainer();
	}

	public function get($consumer_key, $pay_option=null) {
		$accoutAppModel = $this->di->AccountApplicationModel();
		$account_app = $accoutAppModel->getAccountsByKey($consumer_key);
		$accountModel = $this->di->AccountModel();
		$filter = new FieldValueSqlFilter();
		$filter->eq('id', $account_app->account_id);
		$accounts = $accountModel->get()->filter($filter)->exec();
		return $accounts;
	}
}

class Selector {

		const SECONDS = 60;
		const MINUTES = 60;
		const HOURS   = 24;

		protected  $di;

		public $filter;

		public function __construct() {
			$this->di = new DIContainer();
			$this->filter = new FieldValueSqlFilter();
			$this->filter->notEq('email', '');
			$this->filter->_and()->less('emailErrors', EMAIL_ERRORS_LIMIT);
			$this->filter->_and()->eq('subscribe', 1);
		}

		public function setAccountApplicationId($consumer_key) {
			$accountAppModel = $this->di->AccountApplicationModel();
			$account_app = $accountAppModel->getAccountsByKey($consumer_key);
			$this->filter->_and()->eq('id', $account_app->account_id);
		}

		public function setPayFilter($paid) {
			//Платящие - оплата за PASSIVE_NUM_DAYS
			//Неплатящие - нет оплаты за PASSIVE_NUM_DAYS
			if($paid == SendingModel::ALLPAID) return;
			if($paid == SendingModel::PAID)
				$this->filter->_and()->more('pay_time', time() - PASSIVE_NUM_DAYS*self::SECONDS*self::MINUTES*self::HOURS);
			elseif ($paid == SendingModel::NOTPAID)
				$this->filter->_and()->less('pay_time', time() - PASSIVE_NUM_DAYS*self::SECONDS*self::MINUTES*self::HOURS);
		}


		public function PassiveUsers() {
			//Пассивные - ни одного захода за PASSIVE_NUM_DAYS
			$this->filter->_and()->less('enter_time',(time() - PASSIVE_NUM_DAYS*self::SECONDS*self::MINUTES*self::HOURS));
		}

		public function ActiveUsers() {
			//Активные - заход за PASSIVE_NUM_DAYS
			$this->filter->_and()->more('enter_time',(time() - PASSIVE_NUM_DAYS*self::SECONDS*self::MINUTES*self::HOURS));
		}

		public function NewUsers() {
			//Новые - пользователь создан не более NEW_USER_NUM_DAYS назад.
			$this->filter->_and()->more('creationDate',date('Y-m-d H:i:s.u',(time() - NEW_USER_NUM_DAYS*self::SECONDS*self::MINUTES*self::HOURS)));

		}

		public function get(array $row) {
			$selectorNames = SendingModel::getSelectorList();
			$this->setPayFilter($row['paid']);
			$this->$selectorNames[$row['selector']]();
			$accountModel = $this->di->AccountModel();
			$this->setAccountApplicationId($row['app_id']);
			$accounts = $accountModel->get()->filter($this->filter)->exec();
			return $accounts;
		}
}

