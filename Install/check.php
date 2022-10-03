<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

include "./Helpers/Json.php";


if(!Json::get("Helpers/settings.json","token")){
    $_SESSION = [];
    header("location: install/index.php");
}