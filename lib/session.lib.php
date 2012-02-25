<?
require_once('lib/abstract.lib.php');
require_once('lib/singletone.lib.php');

class CacheSessionStorage implements ISessionStorage {
	const SESSION_TTL = 604800; //неделя
	/**
	 * @var ICache
	 */
	private $cache;

	/**
	 * @param ICache $cache
	 */
	public function __construct(ICache $cache) {
		$this->cache = $cache;
	}

	public function save($key, $value) {
		if ($this->cache->lock($key)) $this->cache->set($key, $value, CacheSessionStorage::SESSION_TTL, true);
	}

	public function load($key) {
		return $this->cache->get($key);
	}

	public function exists($key) {
		$ret = $this->cache->get($key);
		return isset($ret);
	}
}

interface ISession{
	public function create();
	public function start();

	/**
	 * @return string
	 */
	public function getSid();

	/**
	 * @return string
	 */
	public function getSig();

	/**
	 * @return string
	 */
	public function setSid($value);

	/**
	 * @return string
	 */
	public function setSig($value);

	/**
	 * @param string $oauthToken
	 */
	public function setOAuthToken($oauthToken);

	/**
	 * @return string
	 */
	public function getOAuthToken();

	public function clean();

	public function saveAll();

	/**
	 * @return string
	 */
	public function getHash();

	/**
	 * @param string $hash
	 */
	public function setHash($hash);
}

class Session implements ISession, IUserContainer{

	public static function obj(ISessionStorage $storage, IGeneric $generic) {
		$varname = 'Session_Singletone_obj';
		if (!isset($GLOBALS[$varname])) {
			$GLOBALS[$varname] = new Session($storage, $generic);
		}
		return $GLOBALS[$varname];
	}

	const SSESSION_ID_COOKIE = 'xld';
	const SSESSION_SIG_COOKIE = '_ft';
	/**
	 * @var
	 */
	private $storage;
	
	/**
	 * @var Generic
	 */
	private $generic;

	/**
	 * @var string
	 */
	private $sid;

	/**
	 * @var string
	 */
	private $sig;

	/**
	 * @var string
	 */
	private $hash;

	/**
	 * @var string
	 */
	private $oauthToken;

	private $user;

	private $consumerKey;

	/**
	 * @param ISessionStorage $storage
	 * @param \IGeneric       $generic
	 */
	public function __construct(ISessionStorage $storage, IGeneric $generic) {
		$this->storage = $storage;
		$this->sid = $_COOKIE[Session::SSESSION_ID_COOKIE];
		$this->sig = $_COOKIE[Session::SSESSION_SIG_COOKIE];
		$this->generic = $generic;
		
		if (!$this->sid)
			$this->create();
	}

	public function __destruct() {
		if ($this->sid) $this->storage->save($this->sid, $this->serialize());
	}

	public function create() {
		$this->sid = md5(uniqid('session',true));
		$this->hash = md5(uniqid('hash',true));
		
		$this->sig = $this->getSignature($this->sid , $this->hash);

		$this->generic->setcookie(Session::SSESSION_ID_COOKIE, $this->sid, time() + 36000, '/');
		$this->generic->setcookie(Session::SSESSION_SIG_COOKIE, $this->sig, time() + 36000, '/');
	}

	public function saveAll() {
		if ($this->sid) $this->storage->save($this->sid, $this->serialize()); }

	public function clean() {
		$res = $this->generic->setcookie(Session::SSESSION_ID_COOKIE, $this->sid, time() - 36000, '/');
		$res = $this->generic->setcookie(Session::SSESSION_SIG_COOKIE, $this->sig, time() - 36000, '/');
		$this->sid = '';
		$this->sig = '';
		unset($this->user);
		unset($this->application);
	}
	/**
	 * @return bool
	 */
	public function getSignature($sid, $hash) {
		return md5($sid . $hash);
	}

	public function start() {
		$str = $this->storage->load($this->sid);
		$arr = unserialize($str);
		$sig = $this->getSignature($this->sid, $arr['hash']);
		if ($sig == $this->sig) $this->unserialize($str);
		return ($sig == $this->sig);
	}

	/**
	 * @return string
	 */
	public function serialize() {
		$arr = array( 'hash' => $this->hash );
		if ($this->oauthToken)
			$arr['oauthToken'] = $this->oauthToken;

		if ($this->consumerKey)
			$arr['consumerKey'] = $this->consumerKey;

		if ($this->user)
			$arr['user'] = $this->user;

		return serialize($arr);
	}

	/**
	 * @param string $string
	 * @return void
	 */
	public function unserialize($string) {
		$arr = unserialize($string);
		if (is_array($arr)) foreach($arr as $k => $v) $this->$k = $v;
		return $arr;
	}

	/**
	 * @return string
	 */
	public function getSid() { return $this->sid; }

	/**
	 * @return string
	 */
	public function getSig() { return $this->sig; }

	/**
	 * @return string
	 */
	public function setSid($value) { $this->sid = $value; }

	/**
	 * @return string
	 */
	public function setSig($value) { $this->sig = $value; }

	/**
	 * @param string $hash
	 */
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * @param string $oauthToken
	 */
	public function setOAuthToken($oauthToken)
	{
		$this->oauthToken = $oauthToken;
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function setConsumerKey($consumerKey) {
		$this->consumerKey = $consumerKey;
	}

	/**
	 * @return string
	 */
	public function getOAuthToken()
	{
		return ($this->oauthToken ? $this->oauthToken : NULL);
	}

	public function getUser() {
		return ($this->user ? $this->user : NULL);
	}

	public function getConsumerKey() {
		return ($this->consumerKey ? $this->consumerKey : NULL);
	}

	public function save() {
		$this->saveAll();
	}
}

?>