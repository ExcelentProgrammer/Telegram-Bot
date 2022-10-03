<?php

use Database\DB;
use Telegram\Bot;

$text = Bot::getText();
if($text == "/start"){
    $user = Bot::getUser();
    DB::table(Vars::getUsersTable())->insert(['userId'=>Bot::chatId(),"firstName"=>$user->first_name,"lastName"=>$user->last_name,"userName"=>$user->username,"page"=>"home"])->run();
}