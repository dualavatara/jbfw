<?php

namespace Admin\Extension\Database;

require_once 'lib/PDODatabase.php';

class Database
	extends \PDODatabase
	implements \Admin\Extension\Database\DatabaseInterface
{}