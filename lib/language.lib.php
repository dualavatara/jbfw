<?
require_once('lib/cache.lib.php');
require_once('lib/singletone.lib.php');
require_once('model/phrase.model.php');

class TranslateException extends Exception {
	
}
/**
 * @property lang
 */
class Language extends Singletone{
	const CACHE_TTL	= 86400;

	private $curr = null;
	private $langs = array();

	private $cache;
	/**
	 * @var IPhraseModel
	 */
	private $model;
	private $isMultilang;

	protected function __construct() {
		$this->langs['ru'] = array('id' => 'ru', 'field' => 'valueRu');
		$this->langs['en'] = array('id' => 'en', 'field' => 'valueEn');
		$this->langs['de'] = array('id' => 'de', 'field' => 'valueDe');

		if (!isset($this->langs[DEFAULT_LANG_ID])) throw new TranslateException("Default language undefined.");
		
		$this->curr = $this->langs[DEFAULT_LANG_ID];
	}

	public function init($isMultilang, ICache $cache, IPhraseModel $model) {
		$this->cache = $cache;
		$this->model = $model;
		$this->isMultilang = $isMultilang;
	}

	public function lang($value = null) {
		if (isset($value)) {
			if (!isset($this->langs[$value])) throw new TranslateException("Language ". $value . " undefined.");
			$this->curr = $this->langs[$value];
		}
		return $this->curr['id'];
	}

	public function field($lang = false) {
		if (!$lang) return $this->curr['field'];

		return $this->langs[$lang]['field'];
	}

	public function translate($str, $lang) {
		if (!$this->isMultilang) return $str;
		
		$key = 'trans'.$this->model->makeHash($str);

		$phrase = '';

		$expired = $this->cache->isExpired($key);
		
		if (!$expired) $phrase = $this->cache->get($key);

		if (!$phrase) {//не нашли в кэше, ищем в базе
			$phrase = $this->model->getPhrase($str, $this->field(DEFAULT_LANG_ID));
			if (empty($phrase)) $this->model->setPhrase($str, $this->field(DEFAULT_LANG_ID));
		};

		if ($expired && $this->cache->lock($key)) $this->cache->set($key, $phrase, Language::CACHE_TTL, true);

		return $phrase[$this->field($lang)] ? $phrase[$this->field($lang)] : $phrase[$this->field(DEFAULT_LANG_ID)];
	}

	/**
	 * @return array
	 * @codeCoverageIgnore
	 */
	public function getCurr() {
		return $this->curr;
	}
}

?>