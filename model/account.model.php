<?
require_once('lib/translate.lib.php');
require_once('lib/model.lib.php');
require_once('model/accountapplication.model.php');
require_once('model/application.model.php');
require_once('model/devices.model.php');

interface IAccountModel {
	public function getUser($uid);
	public function getAuthUser($email, $password);
	public function getUserByEmail($email);
	public function validateUser($email, $password);
	public function createUser($uid, $email, $password);
	public function getErrorText($errno);
	public function unicZDEmailValidate($email, $myEmail = null);
	public function unicEmailValidation($email);
	public function newPassword();
	public function setPassword($password);
}
 
class AccountModel extends Model implements IAccountModel {
	const OK							= 0x0000;
	const NOUSER_ERROR 					= 0x0001;
	const INVALID_PASSWORD_ERROR		= 0x0002;
	const INVALID_EMAIL_ERROR			= 0x0003;

	//User's device sizes
	const MOBILE						= 0x0000;
	const SMALL_TABLET					= 0x0001;
	const LARGE_TABLET					= 0x0002;
	const PC							= 0x0003;

	static private $DEFAULT_FIELDS = array('email', 'nick', 'dev_size', 'zd_email', 'id', 'fb_uid');
	
	private $devices;
	
	/**
	 * @param IDatabase $db
	 */
	public function __construct(IDatabase $db) {
		parent::__construct('accounts', $db);
		$this->field('password', new CharField('password'));
		$this->field('email', new CharField('email'));
		$this->field('nick', new CharField('nick'));
		$this->field('dev_size', new IntField('dev_size'));
		$this->field('zd_email', new CharField('zd_email'));
		$this->field('id', new IntField('id', Field::PRIMARY_KEY));
		$this->field('fb_uid', new CharField('fb_uid'));
		$this->field('name', new CharField('name'));
		$this->field('first_name', new CharField('first_name'));
		$this->field('middle_name', new CharField('middle_name'));
		$this->field('last_name', new CharField('last_name'));
		$this->field('gender', new CharField('gender'));
		$this->field('fb_link', new CharField('fb_link'));
		$this->field('username', new CharField('username'));
		$this->field('hometown', new CharField('hometown'));
		$this->field('fb_email', new CharField('fb_email'));
		$this->field('pic', new IntField('pic'));
		$this->field('pay_count', new IntField('pay_count'));
		$this->field('pay_time', new IntField('pay_time'));
		$this->field('enter_count', new IntField('enter_count'));
		$this->field('enter_time', new IntField('enter_time'));
		$this->field('enter_variable', new CharField('enter_variable'));
		$this->field('subscribe', new IntField('subscribe'));
		$this->field('emailErrors', new IntField('email_errors'));
		$this->field('creationDate', new DateField('creation_date'));

		$this->devices = new DevicesModel($db);
	}

	/**
	 * @param $errno
	 * @return string
	 */
	public function getErrorText($errno, $retArray = false) {
		switch($errno) {
			case self::NOUSER_ERROR: return $this->makeArr(translate('No user found'), 'email', $retArray);
			case self::INVALID_EMAIL_ERROR: return $this->makeArr(translate('Email not valid'), 'email', $retArray);
			case self::INVALID_PASSWORD_ERROR: return $this->makeArr(translate('Password not valid'), 'password', $retArray);
		}
		return '';
	}

	private function makeArr($value, $name, $retArr) {
		if (!$retArr) return $value;
		else return array( 'name' => $name, 'value' => $value );
	}

	public static function passwordHash($password) {
		return md5($password . md5($password));
	}

	public function getSoftUser($uid, $id) {
		if (!$uid || !$id) return $this;
		$this->devices->getUser($uid, $id);
		if (!$this->devices->count())
			return $this;
		$filter = new FieldValueSqlFilter();
		$filter->eq('email', '')->_and()->eq('password', '');
		return $this->get($this->devices->accountId)->filter($filter)->exec();
	}

	public function getUser($uid, $email = '') {
		if ($email) {
			$this->getUserByEmail($email);
			return $this;
		} else {
			$this->devices->getUser($uid);
			if (!$this->devices->count())
				return $this;
			$filter = new FieldValueSqlFilter();
			$filter->eq('email', '');
			$this->get($this->devices->accountId)->filter($filter)->exec();
			return $this;
		}
	}

	public function getAllUsersByUid ($uid) {
		$this->devices->getUser($uid);
		if ($this->devices->count()) {
			$this->get($this->devices->accountId)->all()->exec();
		}
		return $this;
	}

	public function getPersonsFields($id, $fields=null, OSCollectionRequest $osreq = null) {
		if (null == $fields)
			$fields = self::$DEFAULT_FIELDS;
		
		if($id == -1) return array('nick' => 'Guest');
		$this->get();
		if ($id) $this->filter($this->filterExpr()->eq('id', $id));
		if ($osreq) $this->filterOS($osreq);
		$this->exec();
		$coll = $this->collection($fields);
		if (count($coll->getObject()->entry) == 1) return get_object_vars($coll->getObject()->entry[0]);
		elseif (empty($coll->getObject()->entry)) return array();
		else return $coll;
	}

	public function getAuthUser($email, $password) {
		$this->get()->filter($this->filterExpr()->eq('email', $email))->exec();
        if ($this->count()) {
            if ($this[0]->password == $this->passwordHash($password))
                return self::OK;
            else
                return self::INVALID_PASSWORD_ERROR;
        } else
            return self::NOUSER_ERROR;
	}

	public function getUserByEmail($email) {
		$this->get()->filter($this->filterExpr()->eq('email', $email))->exec();
		return $this->count();
	}

	public function validateUser($email, $password) {
		if (!$this->count()) return self::NOUSER_ERROR;

		if ($this[0]->email && $this[0]->email != $email) return self::INVALID_EMAIL_ERROR;

		if ($this[0]->password && $this[0]->password != $this->passwordHash($password)) return self::INVALID_PASSWORD_ERROR;

		if ($password && $email) {
			$this[0]->password = $this->passwordHash($password);
			$this[0]->email = $email;
			if (!$this->unicZDEmailValidate($email, $this[0]->uid)) $this[0]->zd_email = $email;
			$this->update()->exec();
		}
		return self::OK;
	}
	
	public function createUser($uid, $email, $password) {
		if (self::pregValidateEmail($email)) {
			if ($email && $this->unicEmailValidation($email)) return self::INVALID_EMAIL_ERROR;
			$newAcc = array('nick' => 'Player' . $this->rand());
			if ($email && $password) {
				$newAcc['email'] = $email;
				$newAcc['password'] = $this->passwordHash($password);
			}
			if (!$this->unicZDEmailValidate($email)) $newAcc['zd_email'] = $email;
			$this[0] = $newAcc;
			$this->insert()->exec();
			if ($this->count() < 1) throw new ModelException('User creation internal error.');
			$this->devices[0] = array('uid' => $uid, 'accountId' => $this[0]->id);
            $this->devices->insert()->exec();
			if ($this->devices->count() < 1) throw new ModelException('User creation internal error.');
			return self::OK;
		} else return self::INVALID_EMAIL_ERROR;
	}

	function randomPassword ($length = 8) {
		$password = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
		$total = strlen($password) - 1;
		$digit = rand(0, $length - 1);
		$newPassword = '';
		for ($i = 0; $i < $length; $i++) {
			if ($i == $digit) {
				$newPassword .= chr(rand(48, 57));
				continue;
			}
			$newPassword .= $password{rand(0, $total)};
		}
		return $newPassword;
	}
	/**
	 * @return string
	 */
	public function newPassword() {
		$newpass = $this->randomPassword();
		return $this->setPassword($newpass);
//		$this->data[0]['password'] = $this->passwordHash($newpass);
//		$this->update()->exec();
//		return $newpass;
	}

	/**
	 * @param string $password
	 * @return string
	 */
	public function setPassword($password) {
		$this->data[0]['password'] = $this->passwordHash($password);
		$this->update()->exec();
		return $password;
	}

	public function changeEmail($id, $email, $password) {
		if (self::pregValidateEmail($email)) {
			if ($this->unicEmailValidation($email, $id)) return self::INVALID_EMAIL_ERROR;
			$this->reset();
			if (!$this->get($id)->exec()->count()) return self::NOUSER_ERROR;
			if (($this[0]->email && $this[0]->password) || $password) {
				$this[0]->email = $email;
				if ($password) $this[0]->password = $this->passwordHash($password);
				if (!$this[0]->zd_email)
					if (!$this->unicZDEmailValidate($email, $this[0]->uid)) $this[0]->zd_email = $email;
				$this->update()->exec();
				return self::OK;
			} else return self::INVALID_PASSWORD_ERROR;
		} else return self::INVALID_EMAIL_ERROR;
	}

	public function unicZDEmailValidate($email, $myId = null) {
		if (!$email) return true;
		$otherAccountModel = new AccountModel($this->db);
		$filter = new FieldValueSqlFilter();
		$filter->eq('zd_email', $email);
		if ($myId) $filter->_and()->notEq('id', $myId);

		return $otherAccountModel->get()->filter($filter)->exec()->count();
	}

	public function unicEmailValidation($email, $id = null) {
		$accounts = new AccountModel($this->db);
		$filter = new FieldValueSqlFilter();
		$filter->eq('email', $email);
		if ($id != null) $filter->_and()->notEq('id', $id);
		return $accounts->get()->filter($filter)->exec()->count();
	}

	public static function pregValidateEmail($email) {
		return true;
		if ($email)
		//TODO: Validate email
			return preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/', $email);
		return true;
	}
	
	/**
	 * Add the application to user's installed list.
	 * 
	 * @param string $id          User account unique id
	 * @param string $consumer_key Application consumer key
	 * @return void
	 */
	public function addApplication($id, $consumer_key) {
		$model = new AccountApplicationModel($this->db);
		$model[0] = array(
			'account_id' => $id,
			'app_key' => $consumer_key
		);
		$model->insert()->exec();
	}
	
	/**
	 * Remove application from user's installed list.
	 * 
	 * @param string $id          User account unique id
	 * @param string $consumer_key Application consumer key
	 * @return void
	 */
	public function removeApplication($id, $consumer_key) {
		$model = new AccountApplicationModel($this->db);
		$model->delete()->filter(
			$model->filterExpr()->
					eq('account_id', $id)->
					_and()->
					eq('app_key', $consumer_key)
		)->exec();
	}
	
	/**
	 * List of installed applications.
	 * 
	 * @param string $id User account unique id
	 * @return AccountApplicationModel
	 */
	public function getApplications($id=null, $app_key=null) {
		$model = new AccountApplicationModel($this->db);
		$model->get();
		if ($id) $model->filter($model->filterExpr()->eq('account_id', $id));
		if ($app_key) $model->filter($model->filterExpr()->eq('app_key', $app_key));
		$model->exec();

//		$model->get()->filter(
//			$model->filterExpr()->
//					eq('account_id', $id)
//		)->exec();
		return $model;
	}
	
	/**
	 * Restrict direct access to this function.
	 * 
	 * @throws ModelException
	 * @return void
	 */
	public function delete() {
		throw new ModelException('Access to this function is restricted.');
	}
	
	
	/**
	 * Removes user account and related applications.
	 * 
	 * @param string $id
	 * @return void
	 */
	public function remove($id) {
		$apps = $this->getApplications($id);
		foreach ($apps as $app) {
			$this->removeApplication($id, $app->app_key);
		}

		$filter = new FieldValueSqlFilter();
		$filter->eq('id', $id);
		$this->get()->filter($filter);
		parent::delete()->exec();
	}

	public function updateUserField($fields, $user_id) {
		foreach($fields as $field=>$value) {
			if(isset($this->fields[$field]))
				$this->data[0][$field]= $value;
		}
		$this->data[0]['id'] = $user_id;
		parent::update()->exec();
		return $this[0]->allTyped(self::$DEFAULT_FIELDS);
	}

	public function checkMailUnique($mail){
		if($this->unicEmailValidation($mail)
					|| $this->unicZDEmailValidate($mail)) return false;
		else return true;
	}

	public function getUserById($id) {
		$filter = new FieldValueSqlFilter();
		$filter->eq('id', $id);
		$this->get()->filter($filter)->exec();
		if($this->count())return $this[0]->id;
		else return false;
	}

	protected function rand() {
		$result = '';
		for($i = 0; $i < 6; $i++)
			$result .= rand(0,9);
		return $result;
	}

	public static function getUnsubscribeCode($email, $id) {
		return md5(($email).md5(($id)*pi()));
	}

	public function Unsubscribe($email, $code) {
		$filter = new FieldValueSqlFilter();
		$filter->eq('email', $email);
		$this->get()->filter($filter)->exec();
		if($this->count())
			if($code == self::getUnsubscribeCode($this[0]->email, $this[0]->id)) {
				$this[0]->subscribe = 0;
				$this->update()->exec();
			}
		return $this;
	}

	public function updateUsers($field, $id_values_array) {
		$filter = new FieldValueSqlFilter();
		$filter->eq('id', array_keys($id_values_array));
		$this->get()->filter($filter)->exec();
		if(!$this->count()) return;
		foreach($this->data as &$user){
			$user[$field] = $id_values_array[$user['id']];
		}
		$this->update()->exec();
	}

	public function getUserByFbUid($fbProfile) {
		$filter = new FieldValueSqlFilter();
		$filter->eq('fb_uid', $fbProfile['id'])->_or()->eq('email', $fbProfile['email']);
		$this->get()->filter($filter)->exec();
		if ($this->count() && !$this[0]->fb_uid) $this->syncUserWithFB($fbProfile);
		return $this;
	}

	public function syncUserWithFB($fbProfile) {
		if (!$this->count())
			return $this;

		$id = $this[0]->id;
		$newAcc = array(
			'id'			=> $id,
			'email'			=> $fbProfile['email'],
			'nick'			=> $fbProfile['name'],
			'fb_uid'		=> $fbProfile['id'],
			'name'			=> $fbProfile['name'],
			'first_name'	=> $fbProfile['first_name'],
			'last_name'		=> $fbProfile['last_name'],
			'middle_name'	=> $fbProfile['middle_name'],
			'gender'		=> $fbProfile['gender'],
			'fb_link'		=> $fbProfile['link'],
			'fb_email'		=> $fbProfile['email']
		);

		if ($fbProfile['pic'])
			$newAcc['pic'] = $fbProfile['pic'];

		$this[0] = $newAcc;

		return $this->update()->exec()->get($id)->exec();
	}

	public function createUserFromFbProfile($uid, $fbProfile){
		$newAcc = array(
			'email'			=> $fbProfile['email'],
			'nick'			=> $fbProfile['name'],
			'zd_email'		=> $fbProfile['email'],
			'fb_uid'		=> $fbProfile['id'],
			'name'			=> $fbProfile['name'],
			'first_name'	=> $fbProfile['first_name'],
			'last_name'		=> $fbProfile['last_name'],
			'middle_name'	=> $fbProfile['middle_name'],
			'gender'		=> $fbProfile['gender'],
			'fb_link'		=> $fbProfile['link'],
			'fb_email'		=> $fbProfile['email']
		);
		if ($fbProfile['pic'])
			$newAcc['pic'] = $fbProfile['pic'];

		$this[0] = $newAcc;
		$this->insert()->exec();

		if ($this->count() < 1) throw new ModelException('User creation internal error.');
		$this->devices[0] = array('uid' => $uid, 'accountId' => $this[0]->id);
		$this->devices->insert()->exec();
		if ($this->devices->count() < 1) throw new ModelException('User creation internal error.');
		return self::OK;
	}
}
?>