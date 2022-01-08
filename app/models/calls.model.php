<?php
class CallsModel {
    
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPDO();
    }

    public function addCalls($calls) {
        $stmt = $this->pdo->prepare("INSERT INTO `calls` (`i_customer_id`, `date_call`, `i_duration_sec`, `st_dialed_number`, `string_customer_ip`, `st_ip_continent`, `st_phone_continent`) VALUES (?, ?, ?, ?, ?, ?, ?);");
        foreach ($calls as $call) {
            if (!$stmt->execute([ $call['customer_id'], $call['date_call'], $call['duration_sec'], $call['dialed_number'], $call['customer_ip'], $call['ip_continent'], $call['phone_continent'] ])) {
                return false;
            }
        }
        return true;
    }

    public function getStats() {
        $stmt = $this->pdo->prepare("SELECT 
        `calls`.`i_customer_id` AS `customer_id`, 
        COALESCE(`A`.`total_calls_same_continent`,0) AS `total_calls_same_continent`, 
        COALESCE(`A`.`total_duration_same_continent`,0) AS `total_duration_same_continent`,  
        COALESCE(`B`.`total_calls`,0) AS `total_calls`, 
        COALESCE(`B`.`total_duration`,0) AS `total_duration`
        FROM `calls`
        LEFT JOIN (
        SELECT 
        `calls`.`i_customer_id` AS `customer_id`,
        COUNT(`calls`.`id`) AS `total_calls_same_continent`, 
        SUM(`calls`.`i_duration_sec`) AS `total_duration_same_continent`
        FROM `calls`
        WHERE `calls`.`st_ip_continent` = `calls`.`st_phone_continent`
        AND `calls`.`b_muted` = 0
        GROUP BY `calls`.`i_customer_id`
        ) AS `A`
        ON `A`.`customer_id` = `calls`.`i_customer_id`
        LEFT JOIN (
        SELECT 
        `calls`.`i_customer_id` AS `customer_id`,
        COUNT(`calls`.`id`) AS `total_calls`, 
        SUM(`calls`.`i_duration_sec`) AS `total_duration`
        FROM `calls`
        WHERE `calls`.`b_muted` = 0
        GROUP BY `calls`.`i_customer_id`
        ) AS `B`
        ON `B`.`customer_id` = `calls`.`i_customer_id`
        WHERE `calls`.`b_muted` = 0
        GROUP BY `customer_id`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastInsertedID() {
        $stmt = $this->pdo->prepare("SELECT `calls`.`id` FROM `calls` WHERE `calls`.`b_muted` = 0 ORDER BY `calls`.`id` DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) == 0) {
            return 0;
        }
        return $result[0]['id'];
    }
}