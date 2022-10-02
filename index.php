<?php

require_once "./vendor/autoload.php";


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once "./Helpers/Helpers.php";

require_once "./Telegram.php";

require_once "./Variables.php";


require_once "./Helpers/Database.php";

require_once "./Helpers/Lang.php";

require_once "./Bot.php";
