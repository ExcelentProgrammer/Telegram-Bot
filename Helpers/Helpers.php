<?php

use Database\DB;
use Telegram\Bot;

class Helpers
{
    static function log($data)
    {
        return file_put_contents("log.txt", json_encode($data));
    }
    static function requestPost($url,$data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            return curl_error($ch);
        }
        else {
            return $res;
        }
    }
    static function requestGet($url,$headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            return curl_error($ch);
        }
        else {
            return $res;
        }
    }
    static function getUserData()
    {
        return DB::table(Vars::getUsersTable())->select()->where(['userId' => Bot::chatId()])->run()->Data[0];
    }
    static function randNumber($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
  
}