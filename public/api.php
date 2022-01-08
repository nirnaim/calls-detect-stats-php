<?php
include("../app/config.php");
$action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

switch($action) {
    case "uploadFile":
        $response = new stdClass();
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if ($finfo->file($_FILES['file']['tmp_name']) == "text/csv") {
            $calls_controller = new CallsController();
            if($calls_controller->addUploadedFile($_FILES['file']['tmp_name'])) {
                $response->status = "OK";
                $response->message = "Calls added successfully!";
            }
            else {
                $response->status = "ERROR";
                $response->message = "Something went wrong during upload process.";
            }
        }
        else {
            $response->status = "ERROR";
            $response->message = "File Type is not CSV.";
        }
        header('Content-Type: application/json; charset=utf-8');
        print(json_encode($response));
        exit();
    break;

    case "getStats":
        $last_inserted = filter_input(INPUT_POST, "last_inserted", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $calls_controller = new CallsController();
        if($result = $calls_controller->getStats($last_inserted)) {
            $result->status = "UPDATE";
            $result->message = "Updating the table...";
        }
        else {
            $result = new stdClass();
            $result->status = "OK";
            $result->message = "Everything is up to date.";
        }
        header('Content-Type: application/json; charset=utf-8');
        print(json_encode($result));
        exit();
        break;

    default:
        http_response_code(404);
        print("Wrong path.");
        exit();
    break;
}
?>