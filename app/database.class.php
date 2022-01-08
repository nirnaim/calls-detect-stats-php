<?php
// Database Class
class Database {

    private static $instance = NULL;
    private $pdo;

    private function __construct() {
        $dsn = "mysql:dbname=" . DATABASE_DB_NAME . ";host=" . DATABASE_HOST;
        $user = DATABASE_USER_NAME;
        $password = DATABASE_USER_PASS;
        $this->pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }
}