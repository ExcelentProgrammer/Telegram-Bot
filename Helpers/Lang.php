<?php


use Database\DB;
use Telegram\Bot;

class Lang
{
    static function get($text)
    {
        $lang = DB::table(Vars::getUsersTable())->select(['language'])->where(["userId" => Bot::chatId()])->run();
        if ($lang->RowCount != 0) {
            $lang = $lang->Data[0]["language"];
            if ($lang == "uz") {
                $words = json_decode(file_get_contents("lang/uz.json"), true);
                if (!empty($words[$text])) {
                    return $words[$text];
                } else {
                    return $text;
                }
            } elseif ($lang == "ru") {
                $words = json_decode(file_get_contents("lang/ru.json"), true);
                if (!empty($words[$text])) {
                    return $words[$text];
                } else {
                    return $text;
                }
            } elseif ($lang == "en") {
                $words = json_decode(file_get_contents("lang/en.json"), true);
                if (!empty($words[$text])) {
                    return $words[$text];
                } else {
                    return $text;
                }
            }
        } else {
            return $text;
        }
    }
}
