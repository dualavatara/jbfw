<?

/**
 * @throws ModelException
 * @property string $name
 */
abstract class Field {
	const PRIMARY_KEY 		= 0x0001;

	const ORDER_ASC		= 0x0001;
	const ORDER_DESC	= 0x0002;
	
	protected $_name = '';
	protected $flags = 0;
	protected $adminName = '';
	
	public function __construct($name, $flags = 0) {
		$this->_name = $name;
		$this->flags = $flags;
	}
	
	/**
	 * Checks internal field`s flag mask & parameter 
	 * @param integer $flag
	 * @return bool
	 */
	public function is($flag) {
		return ($flag & $this->flags) != 0;
	}

	public function __get($name) {
		$getter = 'get_'.$name;
		if (!method_exists($this, $getter)) throw new ModelException($getter.' is undefined in ' . get_class($this));
		return $this->$getter();
	}
	
	public function __set($name, $value) {
		$setter = 'set_'.$name;
		if (!method_exists($this, $setter)) throw new ModelException($setter.' is undefined in ' . get_class($this));
		$this->$setter($value);
	}
	
	public function get_name() { return $this->_name; }

	abstract public function value($rawvalue);
	abstract public function definition(IDatabase $db, $tablename);
	
	public function rawvalue($value) { return strval($value); }
	
	public function sqlFilter($valarr, IDatabase $db, $op = '=') {
		if (!isset($valarr)) return '';
		if (!is_array($valarr)) $valarr = array($valarr);

		$fieldName = $db->quot($db->escape($this->name));
		switch (count($valarr)) {
			case 0:
				return ($op == '!=') ? 'true' : 'false';
			
			case 1:
				$fieldValue = $db->quot($db->escape(array_shift($valarr)), true);
				return sprintf('%s %s %s', $fieldName, $op, $fieldValue);
			
			default:
				$op = ($op == '!=') ? 'NOT IN' : 'IN';

				$values = array();
				foreach ($valarr as $value) {
					$values[] = $db->quot($db->escape($value), true);
				}
	
				return sprintf('%s %s (%s)', $fieldName, $op, implode(', ', $values));
		}
	}
	public function quotEscapeValue($db, $value) {
		return $db->quot($db->escape($value), true);
	}

}

class IntField extends Field {
	public function value($rawvalue) { return intval($rawvalue); }
	public function definition(IDatabase $db, $tablename) {
		$field = $db->quot($db->escape($this->name));
		if ($this->is(Field::PRIMARY_KEY)) {
			$sql = "\n".$field.' serial NOT NULL,';
			$sql .= "\n".'CONSTRAINT '.$db->quot($db->escape($tablename.'_pkey')).' PRIMARY KEY ('.$field.')';
		} else $sql = "\n".$field.' integer NOT NULL DEFAULT 0';
		return $sql;
	}
}

class RealField extends Field {

    public function value($rawvalue) { return floatval($rawvalue); }

    public function definition(IDatabase $db, $tablename) {
        $field = $db->quot($db->escape($this->name));
        if ($this->is(Field::PRIMARY_KEY)) {
            $sql = "\n".$field.' real NOT NULL DEFAULT 0.0,';
            $sql .= "\n".'CONSTRAINT '.$db->quot($db->escape($tablename.'_pkey')).' PRIMARY KEY ('.$field.')';
        } else $sql = "\n".$field.' real NOT NULL DEFAULT 0.0';
        return $sql;
    }
}

class BoolField extends Field {

	public function value($rawvalue) { return (bool) $rawvalue;}

	public function definition(IDatabase $db, $tablename) {
		$field = $db->quot($db->escape($this->name));
		$sql = "\n".$field.' boolean NOT NULL DEFAULT FALSE';
		return $sql;
	}
}

class VirtualField extends Field {
	public function value($rawvalue) { return $rawvalue; }
	public function rawvalue($value) { return $value; }
	public function definition(IDatabase $db, $tablename) {
		return '';
	}
	public function quotEscapeValue($db, $value) {
		return $value;
	}
}

class FlagsField extends Field {
	private $rawvalue = null;
	public function __construct($name, $flags = 0, $rawval = null) {
		parent::__construct($name, $flags);
		$this->rawvalue = $rawval;
	}
	
	public function value($rawvalue) { return new static($this->name, $this->flags, $rawvalue); }
	public function get() { return $this->rawvalue; }
	/**
	 * Checks is flag bit = 1 in mask
	 * @param integer $flag
	 * @return bool
	 */
	public function check($flag) { return ($this->rawvalue & $flag) > 0; }
	
	public function sqlFilter($valarr, IDatabase $db, $op = '=') {
		$fop = ($op == '=') ? '!=' : '=';
		$fname = $db->quot($db->escape($this->name));
		if (!is_array($valarr)) $valarr = array($valarr);
		if (empty($valarr)) return '';
		if (count($valarr) == 1) {
			$value = array_shift($valarr);
			$res =  '('.$fname . ' & ' . $db->quot($db->escape($value->rawvalue), true) . ') ' . $fop . ' 0';
			return $res;
		} else {
			$res = array();
			foreach($valarr as $value) {
				$res[] = '((' . $fname. ' & ' . $db->quot($db->escape($value->rawvalue), true) . ') ' . $fop . ' 0)';
			}
			return implode(' OR ', $res);
		}
	}
	
	public function definition(IDatabase $db, $tablename) {
		$field = $db->quot($db->escape($this->name));
		$sql = "\n".$field.' bigint NOT NULL DEFAULT 0';
		return $sql;
	}
	
	public function getArray() {
		$bitCount = ceil(log($this->rawvalue, 2));
		
		$result = array();
		for ($bit = 0; $bit < $bitCount; $bit++) {
			$x = pow(2, $bit);
			if ($this->rawvalue | $x)
				$result[] = $x;
		}
		
		return $result;
	}
	
	public static function assemble($flags) {
		if ($flags == null)
			$flags = array();
		
		$result = 0;
		foreach ($flags as $flag) {
			$result = $result | $flag;
		}
		
		return $result;
	}
}

class CharField extends Field {
	public function value($rawvalue) { 
		return strval($rawvalue); 
	}
	
	public function definition(IDatabase $db, $tablename) {
		$field = $db->quot($db->escape($this->name));
		if ($this->is(Field::PRIMARY_KEY)) {
			$sql = "\n".$field.' character varying NOT NULL DEFAULT \'\'::character varying,';
			$sql .= "\n".'CONSTRAINT '.$db->quot($db->escape($tablename.'_pkey')).' PRIMARY KEY ('.$field.')';
		} else $sql = "\n".$field.' character varying NOT NULL DEFAULT \'\'::character varying';
		return $sql;
	}
}

class SerialisedField extends CharField {
	public function value($rawvalue) {
		return unserialize(strval($rawvalue));
	}

	public function rawvalue($value) {
		return serialize($value);
	}
}

class CustomDateTime extends DateTime {
	
	private $format;
	
	public function __construct($time, $format) {
		$this->format = $format;
		parent::__construct($time);
	}
	
	public function __toString() {
		return $this->format($this->format);
	}
}

class CommonDateField extends CharField {
	
	private $format;
	
	public function __construct($name, $format) {
		parent::__construct($name);
		$this->format = $format;
	}
	
	public function definition(IDatabase $db, $tablename) {
		$field = $db->quot($db->escape($this->name));
		$sql = "\n".$field.' timestamp with time zone NOT NULL DEFAULT now()';
		return $sql;
	}
	
	public function value($rawvalue) {
		$value  = (!$rawvalue) ? '@0' : $rawvalue;

		$date = new CustomDateTime($value, $this->format);
		return $date;
	}
}

class DateField extends CommonDateField {
	
	const FORMAT = 'Y-m-d';
	
	public function __construct($name) {
		parent::__construct($name, self::FORMAT);
	}
}

class DateTimeWithTZField extends CommonDateField {
	
	const FORMAT = 'Y-m-d H:i:s';
	
	public function __construct($name) {
		parent::__construct($name, self::FORMAT);
	}
	
	static function nullDate() {
		$nullDate = new DateTime('@0');
		return $nullDate->format(self::FORMAT);
	}

	static function fromTimestamp($timestamp) {
		$nullDate = new DateTime('@' . $timestamp);
		return $nullDate->format(self::FORMAT);
	}
}
?>