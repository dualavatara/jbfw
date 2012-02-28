<?
//require_once('lib/language.lib.php');

define('DATE_SHORT',             1);
define('DATE_LONG',              2);
define('DATE_HUMAN_SHORT',       3);
define('DATE_HUMAN_LONG',        4);
define('DATE_SIMPLE',            5);
define('DATE_DELTA',             6);
define('DATE_MESSAGE',           7);

class Date {
	public static function monthGen ($month = false) {
		$monthHash = array(
			1 => translate('Января'),
			2 => translate('Февраля'),
			3 => translate('Марта'),
			4 => translate('Апреля'),
			5 => translate('Мая'),
			6 => translate('Июня'),
			7 => translate('Июля'),
			8 => translate('Августа'),
			9 => translate('Сентября'),
			10 => translate('Октября'),
			11 => translate('Ноября'),
			12 => translate('Декабря'),
		);
		return $month ? $monthHash[$month] : $monthHash;
	}

	public static function monthNom ($month = false) {
		$monthHash = array(
			1 => translate('Январь'),
			2 => translate('Февраль'),
			3 => translate('Март'),
			4 => translate('Апрель'),
			5 => translate('Май'),
			6 => translate('Июнь'),
			7 => translate('Июль'),
			8 => translate('Август'),
			9 => translate('Сентябрь'),
			10 => translate('Октябрь'),
			11 => translate('Ноябрь'),
			12 => translate('Декабрь'),
		);
		return $month ? $monthHash[$month] : $monthHash;
	}

	public static function weekDayShort ($weekDay = 0) {
		$weekDayHash = array(
			1 => translate('Пн'),
			2 => translate('Вт'),
			3 => translate('Ср'),
			4 => translate('Чт'),
			5 => translate('Пт'),
			6 => translate('Сб'),
			7 => translate('Вс'),
		);
		return ($weekDay ? $weekDayHash[$weekDay] : $weekDayHash);
	}

	public static function weekDay ($weekDay = 0) {
		$weekDayHash = array(
			1 => translate('Понедельник'),
			2 => translate('Вторник'),
			3 => translate('Среда'),
			4 => translate('Четверг'),
			5 => translate('Пятница'),
			6 => translate('Суббота'),
			7 => translate('Воскресенье'),
		);
		return ($weekDay ? $weekDayHash[$weekDay] : $weekDayHash);
	}

	public static function monthDay ($month = 0) {
		$monthDayHash = array(
			1 => 31,
			2 => 28,
			3 => 31,
			4 => 30,
			5 => 31,
			6 => 30,
			7 => 31,
			8 => 31,
			9 => 30,
			10 => 31,
			11 => 30,
			12 => 31,
		);
		return $month ? $monthDayHash[$month] : $monthDayHash;
	}

	// Готовит данные для базы
	public static function assemble ($data = '') {
		return strtotime($data);
	}

	// Готовит вывод для пользователя
	public static function parse ($timestamp = 0, $type = 0) {
		$res = '';
		switch ($type) {
			default:
			case DATE_SHORT:
				$res = Date::parseShort($timestamp);
				break;
			case DATE_LONG:
				$res = Date::parseLong($timestamp);
				break;
			case DATE_HUMAN_SHORT:
				$res = Date::parseHumanShort($timestamp);
				break;
			case DATE_HUMAN_LONG:
				$res = Date::parseHumanLong($timestamp);
				break;
			case DATE_SIMPLE:
				$res = Date::parseSimple($timestamp);
				break;
			case DATE_DELTA:
				$res = Date::parseDelta($timestamp);
				break;
			case DATE_MESSAGE:
				$res = Date::parseMessage($timestamp);
				break;
		}
		return $res;
	}

	public static function parseRfc($timestamp = 0) { // ISO 8601 date RFC 3339 standart http://tools.ietf.org/html/rfc3339
		return date('c', $timestamp);
	}
	
	public static function parseLong ($timestamp = 0) {
		return date('Y-m-d H:i:s', $timestamp);
	}

	public static function parseShort ($timestamp = 0) {
		return date('d.m.Y H:i', $timestamp);
	}

	public static function parseHumanShort ($timestamp = 0) {
		$d = date('d ', $timestamp);
		$m = mb_strtolower(Date::monthGen(date('n', $timestamp)));
		return implode(' ', array($d, $m));
	}

	public static function parseHumanLong ($timestamp = 0) {
		$d = date('d ', $timestamp);
		$m = mb_strtolower(Date::monthGen(date('n', $timestamp)));
		$y = date(' Y', $timestamp);
		return implode(' ', array($d, $m, $y));
	}
	
	public static function parseSimple ($timestamp = 0) {
		return date('d.m.Y', $timestamp);
	}
	
	public static function parseDelta ($timestamp = 0) {
		$res = '';
		$d = time() - $timestamp;
		if ($d <= 60) {
			$res = translate('1 мин. назад');
		} else if ($d > 60 && $d <= 60 * 59) {
			$res = sprintf(translate('%d мин. назад'), ceil($d / 60));
		} else if ($d > 60 * 59 && $d < 60 * 60 * 24) {
			$res = sprintf(translate('%d ч. назад'), ceil($d / 60 / 60));
		} else {
			$res = self::parseHumanLong($timestamp);
		}
		return $res;
	}
	
	public static function parseMessage($timestamp = 0) {
		$d = date('d ', $timestamp);
		$m = mb_strtolower(Date::monthGen(date('n', $timestamp)));
		$res = implode(' ', array($d, $m));
		$res = sprintf(translate('%s в %s'), $res, date('H:i', $timestamp));
		return $res;
	}
}

?>