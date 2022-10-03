<?php

use Telegram\Bot;
use Database\DB;

Bot::handler(['text'=>"salom"],function(){
    Bot::sendMessage("salom");
});
