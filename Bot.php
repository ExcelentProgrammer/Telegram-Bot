<?php

use Telegram\Bot;
use Database\DB;

Bot::handler(['command' => "start"], function () {
    Bot::sendMessage("Assalomu alaykum");
});