<?php
/**
 * Created by JetBrains PhpStorm.
 * User: g.sokolik
 * Date: 13.12.11
 * Time: 12:18
 */

require_once ('lib/singletone.lib.php');
require_once ('lib/abstract.lib.php');

class Logger extends Singletone{

	//Modes
	const DEBUG_MODE		= 0;
	const PRODUCTION_MODE	= 3;

	//Levels
	const DEBUG_LVL			= 0;
	const INFO_LVL			= 1;
	const WARNING_LVL		= 2;
	const ERROR_LVL			= 3;
	const CRITICAL_LVL		= 4;

	private $commonTags = array(
		self::DEBUG_LVL			=> '[DEBUG] ',
		self::INFO_LVL			=> '[INFO] ',
		self::WARNING_LVL		=> '[WARNING] ',
		self::ERROR_LVL			=> '[ERROR] ',
		self::CRITICAL_LVL		=> '[CRITICAL] '
	);

	private $customTags = array();

	private $nativeErrCodeMap = array(
		E_ERROR				=> self::CRITICAL_LVL,
		E_WARNING			=> self::WARNING_LVL,
		E_PARSE				=> self::CRITICAL_LVL,
		E_NOTICE			=> self::INFO_LVL,
		E_CORE_ERROR		=> self::CRITICAL_LVL,
		E_CORE_WARNING		=> self::WARNING_LVL,
		E_COMPILE_ERROR		=> self::CRITICAL_LVL,
		E_COMPILE_WARNING	=> self::WARNING_LVL,
		E_USER_ERROR		=> self::ERROR_LVL,
		E_USER_WARNING		=> self::WARNING_LVL,
		E_USER_NOTICE		=> self::INFO_LVL,
		E_STRICT			=> self::DEBUG_LVL,
		E_RECOVERABLE_ERROR	=> self::ERROR_LVL,
		E_DEPRECATED		=> self::DEBUG_LVL,
		E_USER_DEPRECATED	=> self::DEBUG_LVL
	);

	protected $mode;
	public function __construct() {
		if (DEBUG_MODE) $this->setMode(self::DEBUG_MODE);
		else $this->setMode(self::PRODUCTION_MODE);
	}

	public function setMode($mode)
	{
		$this->mode = $mode;
	}

	public function getMode()
	{
		return $this->mode;
	}

	public function debug($msg) { $this->log(self::DEBUG_LVL, $msg); }

	public function info($msg) { $this->log(self::INFO_LVL, $msg); }

	public function warning($msg) { $this->log(self::WARNING_LVL, $msg); }

	public function error($msg) { $this->log(self::ERROR_LVL, $msg); }

	public function critical($msg) { $this->log(self::CRITICAL_LVL, $msg); }

	public function log($lvl, $msg, $args = null, $kwargs = null) {
		//@todo realise usage of $args and $kwargs variables
		if ($lvl >= $this->mode) {
			if (!is_array($msg) && !is_object($msg)) @error_log($this->getLogTag($lvl) . $msg);
			elseif ($msg instanceof IPrintable) @error_log($this->getLogTag($lvl) . $msg->toString());
			else @error_log($this->getLogTag($lvl) . var_export($msg, true));
		}
	}

//	public function exception($msg, $args) { $this->log(self::ERROR_LVL, $msg, $args); }

	private final function getLogTag ($lvl) {
		if (isset ($this->commonTags[$lvl])) return $this->commonTags[$lvl];
		elseif (isset ($this->commonTags[$lvl])) return $this->commonTags[$lvl];
		else return '';
	}

	public function nativeLog($lvl, $msg) {
		if (isset($this->nativeErrCodeMap[$lvl])) $this->log($this->nativeErrCodeMap[$lvl], $msg);
		else $this->log(self::CRITICAL_LVL, $msg);
	}

	//@todo realise settings of custom levels
}

?>