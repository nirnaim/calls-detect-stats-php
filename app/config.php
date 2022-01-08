<?php
define("APP_ENV", "DEV");

// SQL Credentials
define("DATABASE_HOST", "localhost");
define("DATABASE_USER_NAME", "commapeak_user");
define("DATABASE_DB_NAME", "commapeak_db");
define("DATABASE_USER_PASS", "CoMmApEaK!");

require_once("../app/database.class.php");
require_once("../app/models/calls.model.php");
require_once("../app/models/continent.model.php");
require_once("../app/controllers/calls.controller.php");
?>