<?php

use Database\DB;
use Telegram\Bot;

$text = Bot::getText();
if (Bot::isBlock()) {
    Bot::sendMessage("Kechirasiz Siz Botdan Foydalana olmaysiz");
    exit();
}
if (!Bot::isActive())
    Bot::setActive(true);


if ($text == preg_match("/(.*)startd(.*)/",$text)) {
    $user = Bot::getUser();
    $res = DB::table(Vars::getUsersTable())->select()->where(['userId' => Bot::chatId()])->run()->RowCount;
    if ($res == 0) {
        $res = DB::table(Vars::getUsersTable())->insert(['userId' => Bot::chatId(), "firstName" => $user->first_name, "lastName" => $user->last_name, "userName" => $user->username, "page" => "home"])->run();
    }
}