<?php

class Json
{

    static function put($json_file_name, $data)
    {
        if(!file_exists($json_file_name)){
            file_put_contents($json_file_name,"{}");
        }
        $read = file_get_contents($json_file_name);
        $dd = json_decode($read);
        foreach ($data as $key => $d) {
            $dd->$key = $d;
        }
        return file_put_contents($json_file_name,json_encode($dd));
    }
    static function get($json_file_name, $key)
    {
        if(!file_exists($json_file_name)){
            file_put_contents($json_file_name,"{}");
        }
        $read = file_get_contents($json_file_name);
        $dd = json_decode($read);
        if(isset($dd->$key)){
            return $dd->$key;
        }else{
            return false;
        }
    }
    static function remove($json_file_name, $data)
    {
        if(!file_exists($json_file_name)){
            file_put_contents($json_file_name,"{}");
        }
        $read = file_get_contents($json_file_name);

        $dd = json_decode($read);
        if (gettype($data) == "string") {
            unset($dd->$data);
        } else {
            foreach ($data as $d) {
                unset($dd->$d);
            }
        }
        return file_put_contents($json_file_name,json_encode($dd));
    }
}

