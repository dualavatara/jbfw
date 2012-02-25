<?php

namespace Admin\Extension\Database;

require_once 'lib/db.pg.lib.php';

class Database
	extends \PGDatabase
	implements \Admin\Extension\Database\DatabaseInterface
{}