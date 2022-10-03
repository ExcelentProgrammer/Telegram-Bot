<?php

use Telegram\Bot;
use Database\DB;

Bot::handler(['command'=>"start"],function(){
    $firstName = Bot::getUser()->first_name;
    Bot::sendMessage("Assalomu alaykum $firstName Botga hush kelibsiz");
});

Bot::handler(['text'=>"salom"],function(){
    Bot::sendMessage("salom");
});