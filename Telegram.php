<?php

namespace Telegram;

use CURLFile;
use Database\DB;
use Helpers;
use Lang;
use Vars;

class Bot
{
    static function bot($method, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $_ENV['TOKEN'] . "/" . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            print_r(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }

    static function handler(array $data, object $function)
    {
        $text = $data['text'] ?? null;
        $ttext = str_replace("\\", "\\\\", $data['text'] ?? null);
        $ttext = str_replace("\"", "\\\"", $ttext ?? null);
        $ttext = str_replace("/", "\/", $ttext ?? null);
        $command = $data['command'] ?? null;
        $type = $data['type'] ?? null;
        $inlineQuery = $data['inline'] ?? null;
        $qquery = str_replace("\\", "\\\\", $data['inlineQuery'] ?? null);
        $qquery = str_replace("\"", "\\\"", $qquery ?? null);
        $qquery = str_replace("/", "\/", $qquery ?? null);
        $callback = $data['callback'] ?? null;
        if (($text != null) and (Bot::getText()) and preg_match("/" . str_replace("*", "(.*)", $ttext) . "/", Bot::getText())) {
            preg_match_all("/" . str_replace("*", "(.*)", $ttext) . "/", Bot::getText(), $vars);
            call_user_func($function, $vars);
            exit();
        } elseif ((($callback != null) and (Bot::getCallBackData()) and preg_match("/" . str_replace("*", "(.*)", $callback) . "/", Bot::getCallBackData()))) {
            preg_match_all("/" . str_replace("*", "(.*)", $callback) . "/", Bot::getCallBackData(), $vars);
            call_user_func($function, $vars);
            exit();
        } elseif ($command != null and "/" . $command == self::getText()) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif ($type != null and $type == "text" and !empty(Bot::getUpdateArray()['message']['text'])) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif ($type != null and $type == "video" and !empty(Bot::getUpdateArray()['message']['video'])) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif ($type != null and $type == "photo" and !empty(Bot::getUpdateArray()['message']['photo'])) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif ($type != null and $type == "document" and !empty(Bot::getUpdateArray()['message']['document'])) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif ($type != null and $type == "audio" and !empty(Bot::getUpdateArray()['message']['audio'])) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif ($type != null and $type == "contact" and !empty(Bot::getUpdateArray()['message']['contact']['phone_number'])) {
            $vars = [Bot::getText()];
            call_user_func($function,$vars);
            exit();
        } elseif (($inlineQuery != null) and (Bot::getInlineQuery()) and (preg_match("/" . str_replace("*", "(.*)", $qquery) . "/", Bot::getInlineQuery()->query))) {
            preg_match_all("/" . str_replace("*", "(.*)", $qquery) . "/", Bot::getInlineQuery()->query, $vars);
            call_user_func($function, [$vars]);
            exit();
        }
    }

    static function isNewUser()
    {
        $user = DB::table(\Vars::getUsersTable())->select()->where(['userId' => self::chatId()])->run()->RowCount;
        if ($user == 0) {
            return true;
        }
        return false;
    }

    static function getUpdate()
    {
        return json_decode(file_get_contents("php://input"));
    }

    static function getUpdateArray()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    static function getCallBackData()
    {

        return self::getUpdate()->callback_query->data;
    }

    static function inlineKeyboard($data)
    {
        return json_encode(['inline_keyboard' => $data]);
    }

    static function keyboard($data)
    {
        return json_encode(['keyboard' => $data, "resize_keyboard" => true]);
    }

    static function removeKeyboard()
    {
        return json_encode(['remove_keyboard' => true]);
    }

    static function getCallBackMessageId()
    {
        return self::getUpdate()->callback_query->message->message_id;
    }

    static function getMessageId()
    {
        return self::getUpdate()->message->message_id;
    }

    static function chatId()
    {
        if (!empty(self::getUpdateArray()["callback_query"]["from"]["id"])) {
            return self::getUpdateArray()["callback_query"]["from"]["id"];
        } else {
            return self::getUpdate()->message->chat->id;
        }
    }

    static function getText()
    {
        return self::getUpdate()->message->text;
    }

    static function sendMessage($data)
    {
        if (gettype($data) == "string") {
            $d['chat_id'] = self::chatId();
            $d['text'] = $data;
            self::bot("sendMessage", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            self::bot("sendMessage", $data);
        }
    }

    static function getInlineQuery()
    {
        return Bot::getUpdate()->inline_query;
    }

    static function answerInlineQuery($results)
    {
        $id = Bot::getInlineQuery()->id;

        return self::bot("answerInlineQuery", ["cache_time" => 1, 'inline_query_id' => $id, "results" => json_encode($results)]);
    }

    static function editMessageText(array $data)
    {
        if (empty($data['chat_id'])) {
            $data['chat_id'] = self::chatId();
        }
        return self::bot("editMessageText", $data);

    }

    static function deleteMessage($data)
    {
        if (empty($data['chat_id'])) {
            $data['chat_id'] = self::chatId();
        }
        self::bot("deleteMessage", $data);
    }

    static function sendVideo($data)
    {
        if (gettype($data) == "string") {
            $d['chat_id'] = self::chatId();
            $d['video'] = new CURLFile($data);

            self::bot("sendVideo", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            $data['video'] = new CURLFile($data['video']);

            self::bot("sendVideo", $data);
        }
    }

    static function sendAudio($data)
    {
        if (gettype($data) == "string") {
            $d['chat_id'] = self::chatId();
            $d['audio'] = new CURLFile($data);

            self::bot("sendAudio", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            $data['audio'] = new CURLFile($data['audio']);

            self::bot("sendAudio", $data);
        }
    }

    static function sendPhoto($data)
    {
        if (gettype($data) == "string") {
            $d['chat_id'] = self::chatId();
            $d['photo'] = new CURLFile($data);
            return self::bot("sendPhoto", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            $data['photo'] = new CURLFile($data['photo']);
            return self::bot("sendPhoto", $data);
        }
    }

    static function editMessageMedia($data)
    {

        if (empty($data['chat_id'])) {
            $data['chat_id'] = self::chatId();
        }
        $data['media'] = json_encode($data['media']);
        return self::bot("editMessageMedia", $data);

    }

    static function sendDocument($data)
    {
        if (gettype($data) == "string") {
            $d['chat_id'] = self::chatId();
            $d['document'] = new CURLFile($data);

            self::bot("sendDocument", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            $data['document'] = new CURLFile($data['document']);

            self::bot("sendDocument", $data);
        }
    }

    static function getContact()
    {
        return self::getUpdate()->message->contact->phone_number;
    }

    static function getUser()
    {
        if (!empty(self::getUpdateArray()["callback_query"]["from"])) {
            return self::getUpdate()->callback_query->from;
        } else {
            return self::getUpdate()->message->from;
        }
    }

    static function getPage()
    {
        $res = DB::table(\Vars::getUsersTable())->select(['page'])->where(['userId' => Bot::chatId()])->run()->Data[0];
        return $res['page'];

    }

    static function updatePage($page)
    {
        return DB::table(\Vars::getUsersTable())->update(['page' => $page])->where(['userId' => Bot::chatId()])->run();

    }

    static function updateUser($data, $where)
    {
        return DB::table(Vars::getUsersTable())->update($data)->where($where)->run();
    }

    static function editMessageCation($data)
    {
        if (empty($data['chat_id'])) {
            $data['chat_id'] = self::chatId();
        }
        return self::bot("editMessageCaption", $data);
    }


}