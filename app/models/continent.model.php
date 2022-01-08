<?php
class ContinentModel {
    
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPDO();
    }

    public function getContinentByPhone($phone) {
        $phone = trim($phone);
        $perfixes_array = [substr($phone, 0, 6), substr($phone, 0, 5), substr($phone, 0, 4), substr($phone, 0, 3), substr($phone, 0, 2), substr($phone, 0, 1)];
        $stmt = $this->pdo->prepare("SELECT * FROM `phone_to_continent` WHERE `st_phone_prefix` IN (?, ?, ?, ?, ?, ?) ORDER BY LENGTH(`phone_to_continent`.`st_phone_prefix`) DESC LIMIT 1;");
        $stmt->execute($perfixes_array);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}