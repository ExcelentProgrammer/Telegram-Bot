<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE)
    session_start();


require_once "./Install/check.php";

require_once "./vendor/autoload.php";

require_once "./Helpers/Json.php";

require_once "./Helpers/Helpers.php";

require_once "./Telegram.php";

require_once "./Variables.php";

require_once "./Helpers/Database.php";

require_once "./Helpers/Lang.php";

require_once "./Helpers/Users.php";

require_once "./Bot.php";
