<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

include "../Helpers/Json.php";

$p = $_POST;
$token = $p['token'];
$bazaName = $p['baza-name'];
$username = $p['username'];
$password = $p['password'];
$host = $p['host'];

$file = "../Helpers/settings.json";


function request($url)
{
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ]
    );
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        return curl_error($ch);
    } else {
        return $res;
    }
}




if (preg_match("/^[0-9]{1,20}:(.*)/", $token)) {
    Json::put($file, ["token" => $token, "bazaName" => $bazaName, "username" => $username, "password" => $password, "host" => $host]);

    $domain = $_SERVER['HTTP_HOST'];
    $url = $_SERVER['REQUEST_URI'];
    $url = str_replace("/Install/request.php", "", $url);
    $domain = "https://" . $domain . "" . $url . "/index.php";
    $res = request("https://api.telegram.org/bot" . $token . "/setWebhook?url=" . $domain);
    $res = json_decode($res);
    if (!$res->ok) {
        $_SESSION["error"] =  $res->description;
        header("location: index.php");
    } else {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$bazaName", $username, $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("location: index.php");
        }


        $file = "users.sql";
        $sql = fopen($file, "r+");
        $sql = fread($sql, filesize($file));


        $res = $pdo->query($sql);
        if ($res) {
            $_SESSION["done"] =  "ok";
            unset($_SESSION['error']);
            header("location: index.php");
        } else {
            $_SESSION["error"] =  "Bazada mummo mavjud bazada users jadvali yo'qligiga ishonch hosil qiling";
            header("location: index.php");
        }
    }
} else {
    echo "token Nato'g'ri kiritildi";
}
