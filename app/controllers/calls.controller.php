<?php
// CALSS CONTROLLER

class CallsController
{
    public function addUploadedFile($filepath) {
        $row = 0;
        $calls = [];
        if (($handle = fopen($filepath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, NULL, ",")) !== FALSE) {
                $calls[$row]['customer_id']         = filter_var($data[0],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $calls[$row]['date_call']           = filter_var($data[1],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $calls[$row]['duration_sec']        = filter_var($data[2],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $calls[$row]['dialed_number']       = filter_var($data[3],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $calls[$row]['customer_ip']         = filter_var($data[4],FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $calls[$row]['ip_continent']    = $this->ipGetContinent($calls[$row]['customer_ip']);
                $calls[$row]['phone_continent'] = $this->phoneGetContinent($calls[$row]['dialed_number']);
                $row++;
            }
            fclose($handle);
            $calls_model = new CallsModel();
            if($calls_model->addCalls($calls)) {
                return true;
            }
        }
        return false;
    } 

    public function getStats($last_inserted = NULL) {
        $result = new stdClass();
        $calls_model = new CallsModel();
        $db_last_inserted = $calls_model->getLastInsertedID();
        if ($last_inserted != NULL) {
            if ($last_inserted == $db_last_inserted) {
                return NULL;
            }
        }
        $result->stats = $calls_model->getStats();
        $result->last_inserted = $db_last_inserted;
        return $result;
    } 

    // It's better to do it in Bulk, but it's only for paid, and this api key it's free one
    public function ipGetContinent($ip) {  
        $apiKey = "a99c04e792834498830996e8cc4bd465";
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=".$apiKey."&ip=".$ip;
        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $ip_data = json_decode(curl_exec($cURL));
        if(isset($ip_data->continent_name)) {
            return $ip_data->continent_code;
        }
        return "X";
    }

    public function phoneGetContinent($phone) {
        $continent_model = new ContinentModel();
        if($continent_record = $continent_model->getContinentByPhone($phone)) {
            return $continent_record['st_continent'];
        }
        return "X";
    }
}