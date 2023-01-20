<?php


use Database\DB;
use Telegram\Bot;

class Lang
{
    static function get($text, $lang = Null)
    {
        $langg = DB::table(Vars::getUsersTable())->select(['language'])->where(["userId" => Bot::chatId()])->run();
        if ($langg->RowCount != 0) {
            if ($lang == Null)
                $lang = $langg->Data[0]["language"];
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

    static function translate($text, $to_lan = Null, $from_lan = Null)
    {
        $lang = DB::table(Vars::getUsersTable())->select(['language'])->where(["userId" => Bot::chatId()])->run();
        if ($lang->RowCount != 0) {
            $lang = $lang->Data[0]["language"];

            if ($from_lan == Null) {
                $from_lan = "auto";
            }
            if ($to_lan == Null) {
                if ($lang != $from_lan) {
                    $to_lan = $lang;
                } else {
                    return $text;
                }
            }
            $ch = curl_init();
            curl_setopt_array(
                $ch,
                [
                    CURLOPT_URL => "https://translate.up.railway.app/api/v1/translate/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => ['text' => $text, "from_lang" => $from_lan, "to_lang" => $to_lan],
                ]
            );
            return json_decode(curl_exec($ch))->res;
        }
    }
}


