<?php

namespace Telegram;

use CURLFile;
use Database\DB;
use Json;
use Vars;

class Bot
{
    static function bot($method, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . Json::get("Helpers/settings.json", "token") . "/" . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            return curl_error($ch);
        } else {
            return json_decode($res);
        }
    }

    static function getToken()
    {
        return Json::get("Helpers/settings.json", "token");
    }

    static function handler(array $data, $function)
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
        if (($text != null) and (Bot::getText()) and preg_match("/^" . str_replace("*", "(.*)", $ttext) . "$/", Bot::getText())) {
            preg_match_all("/^" . str_replace("*", "(.*)", $ttext) . "$/", Bot::getText(), $vars);
            call_user_func($function, $vars);
            exit();
        } elseif ((($callback != null) and (Bot::getCallBackData()) and preg_match("/^" . str_replace("*", "(.*)", $callback) . "$/", Bot::getCallBackData()))) {
            preg_match_all("/^" . str_replace("*", "(.*)", $callback) . "$/", Bot::getCallBackData(), $vars);
            call_user_func($function, $vars);
            exit();
        } elseif ($command != null and "/" . $command == self::getText()) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
            exit();
        } elseif ($type != null and $type == "text" and !empty(Bot::getUpdateArray()['message']['text'])) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
            exit();
        } elseif ($type != null and $type == "video" and !empty(Bot::getUpdateArray()['message']['video'])) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
            exit();
        } elseif ($type != null and $type == "photo" and !empty(Bot::getUpdateArray()['message']['photo'])) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
            exit();
        } elseif ($type != null and $type == "document" and !empty(Bot::getUpdateArray()['message']['document'])) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
            exit();
        } elseif ($type != null and $type == "audio" and !empty(Bot::getUpdateArray()['message']['audio'])) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
            exit();
        } elseif ($type != null and $type == "contact" and !empty(Bot::getUpdateArray()['message']['contact']['phone_number'])) {
            $vars = [Bot::getText()];
            call_user_func($function, $vars);
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
        } elseif (Bot::getInlineQuery()) {
            return self::getInlineQuery()->from->id;
        } else {
            return self::getUpdate()->message->chat->id;
        }
    }

    static function isTextMessage()
    {
        if (!empty(self::getUpdateArray()["callback_query"]["from"]["id"])) {
            return false;
        } else {
            return true;
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
            $d['text'] = ((string)$data);
            $res = self::bot("sendMessage", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            $res = self::bot("sendMessage", $data);
        }
        return $res;
    }

    static function getMe()
    {
        return Bot::bot("getMe")->result;
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

    static function getChatMember($chat_id, $user_id = Null)
    {
        if ($user_id == Null) {
            $user_id = Bot::chatId();
        }
        $res = self::bot("getChatMember", ['user_id' => $user_id, "chat_id" => $chat_id]);
        $status = $res->result->status;
        if ($status == "creator" or $status == "administrator" or $status == "member" or $res->ok == false) {
            return true;
        }
        return false;
    }

    static function getChatMemberStatus($chat_id, $user_id = Null)
    {
        if ($user_id == Null) {
            $user_id = Bot::chatId();
        }
        return self::bot("getChatMember", ['user_id' => $user_id, "chat_id" => $chat_id])->result->status;

    }

    static function getChat($id = Null)
    {
        if ($id == Null) {
            $id = Bot::chatId();
        }
        return Bot::bot("getChat", ['chat_id' => $id])->result;
    }

    static function editMessageText($data)
    {
        if (gettype($data) != "string") {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            if (empty($data['message_id'])) {
                if (self::isTextMessage()) {
                    $data['message_id'] = self::getMessageId() + 1;
                } else {
                    $data['message_id'] = self::getCallBackMessageId();
                }
            }
        } else {
            $data = ['text' => $data];
            if (self::isTextMessage()) {
                $data['message_id'] = self::getMessageId() + 1;
            } else {
                $data['message_id'] = self::getCallBackMessageId();
            }
            $data['chat_id'] = self::chatId();
        }


        return self::bot("editMessageText", $data);

    }

    static function copyMessage($data = [])
    {
        if (gettype($data) != "string") {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            if (empty($data['from_chat_id'])) {
                $data['from_chat_id'] = self::chatId();
            }
            if (empty($data['message_id'])) {
                if (self::isTextMessage()) {
                    $data['message_id'] = self::getMessageId();
                } else {
                    $data['message_id'] = self::getCallBackMessageId();
                }
            }
        } else {
            $data = ['text' => $data];
            if (self::isTextMessage()) {
                $data['message_id'] = self::getMessageId();
            } else {
                $data['message_id'] = self::getCallBackMessageId();
            }
            $data['chat_id'] = self::chatId();
            $data['from_chat_id'] = self::chatId();
        }


        return self::bot("copyMessage", $data);

    }

    static function forwardMessage($data = [])
    {
        if (gettype($data) != "string") {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            if (empty($data['from_chat_id'])) {
                $data['from_chat_id'] = self::chatId();
            }
            if (empty($data['message_id'])) {
                if (self::isTextMessage()) {
                    $data['message_id'] = self::getMessageId();
                } else {
                    $data['message_id'] = self::getCallBackMessageId();
                }
            }
        } else {
            $data = ['text' => $data];
            if (self::isTextMessage()) {
                $data['message_id'] = self::getMessageId();
            } else {
                $data['message_id'] = self::getCallBackMessageId();
            }
            $data['chat_id'] = self::chatId();
            $data['from_chat_id'] = self::chatId();
        }


        return self::bot("forwardMessage", $data);

    }


    static function deleteMessage($data = Null)
    {
        if ($data == Null) {
            if (self::isTextMessage()) {
                $data['message_id'] = self::getMessageId() + 1;
            } else {
                $data['message_id'] = self::getCallBackMessageId();
            }
            $data['chat_id'] = self::chatId();
        };
        return self::bot("deleteMessage", $data);
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

    static function getAllUsers()
    {
        return DB::table(Vars::getUsersTable())->select()->run()->Data;
    }

    static function getActiveUsers()
    {
        return DB::table(Vars::getUsersTable())->select()->where(['status' => "1"])->run()->Data;
    }

    static function User($id = null)
    {
        if ($id == null) {
            $id = Bot::chatId();
        }
        return DB::table(Vars::getUsersTable())->select()->where(['userId' => $id])->run()->Data[0];
    }

    static function setActive(bool $isActive, $user_id = null)
    {
        if ($user_id == null) {
            $user_id = Bot::chatId();
        }
        if ($isActive) {
            $isActive = "1";
        } else {
            $isActive = "0";
        }
        return DB::table(Vars::getUsersTable())->update(['status' => $isActive])->where(["userId" => $user_id])->run();
    }

    static function setBlock(bool $isActive, $user_id = null)
    {
        if ($user_id == null) {
            $user_id = Bot::chatId();
        }
        if ($isActive) {
            $isActive = "1";
        } else {
            $isActive = "0";
        }
        return DB::table(Vars::getUsersTable())->update(['isBlock' => $isActive])->where(["userId" => $user_id])->run();
    }

    static function isBlock($user_id = null)
    {
        if ($user_id == null) {
            $user_id = Bot::chatId();
        }
        $res = DB::table(Vars::getUsersTable())->select()->where(["userId" => $user_id])->run()->Data[0];
        if ($res['isBlock'] == '1') {
            return true;
        }
        return false;
    }

    static function isActive($user_id = null)
    {
        if ($user_id == null) {
            $user_id = Bot::chatId();
        }
        $res = DB::table(Vars::getUsersTable())->select()->where(["userId" => $user_id])->run()->Data[0];
        if ($res['status'] == '1') {
            return true;
        }
        return false;
    }

    static function getNotActiveUsers()
    {
        return DB::table(Vars::getUsersTable())->select()->where(['status' => "0"])->run()->Data;
    }

    static function sendPhotoFileId($data)
    {
        if (gettype($data) == "string") {
            $d['chat_id'] = self::chatId();
            $d['photo'] = $data;
            return self::bot("sendPhoto", $d);
        } else {
            if (empty($data['chat_id'])) {
                $data['chat_id'] = self::chatId();
            }
            return self::bot("sendPhoto", $data);
        }
    }

    static function editMessageMedia($data)
    {
        if (empty($data['chat_id'])) {
            $data['chat_id'] = self::chatId();
        }
        if (empty($data['message_id'])) {
            if (self::isTextMessage()) {
                $data['message_id'] = self::getMessageId() + 1;
            } else {
                $data['message_id'] = self::getCallBackMessageId();
            }
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

    static function updateUser($data, $where = [])
    {
        $where['userId'] = Bot::chatId();
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