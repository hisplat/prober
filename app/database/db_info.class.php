<?php

include_once(dirname(__FILE__) . "/../config.php");

class db_info extends database {

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new db_info();
        return self::$instance;
    }

    private function __construct() {
        $this->init(MYSQL_DATABASE);
    }

    private function doQuery($sql) {
        $result = $this->query($sql);
        $resArray = array();
        if ($result !== false) {
            while ($tmpArray = $result->fetch(PDO::FETCH_ASSOC)) {
                $tmpArray = $this->do_unescape($tmpArray);
                if (isset($tmpArray["id"])) {
                    $resArray[$tmpArray["id"]] = $tmpArray;
                } else {
                    $resArray[] = $tmpArray;
                }
            }
        }
        return $resArray;
    }

    public function all() {
        $infotable = MYSQL_PREFIX . "info";
        return $this->get_all_table($infotable, "", "ORDER BY id DESC");
    }

    public function get_by_token($token) {
        $infotable = MYSQL_PREFIX . "info";

        $token = $this->escape($token);
        return $this->get_one_table($infotable, "token = $token");
    }

    public function update_message($token, $message, $name, $contact) {
        $infotable = MYSQL_PREFIX . "info";
        $one = $this->get_by_token($token);
        $now = time(NULL);
        $ip = get_client_ip();
        $loc = iplookup($ip);
        $ip = "$loc($ip)";
        if (empty($one)) {
            $ret = $this->insert($infotable, array(
                "message" => $message,
                "name" => $name,
                "contact" => $contact,
                "token" => $token,
                "time" => $now,
                "messageip" => $ip,
            ));
        } else {
            $token = $this->escape($token);
            $ret = $this->update($infotable, array(
                "message" => $message,
                "name" => $name,
                "contact" => $contact,
                "time" => $now,
                "messageip" => $ip,
            ), "token = $token");
        }
        return $ret;
    }

    public function update_filename($token, $filename) {
        $infotable = MYSQL_PREFIX . "info";
        $now = time(NULL);
        $ip = get_client_ip();
        $loc = iplookup($ip);
        $ip = "$loc($ip)";
        $one = $this->get_by_token($token);
        if (empty($one)) {
            $ret = $this->insert($infotable, array(
                "token" => $token,
                "filename" => $filename,
                "time" => $now,
                "uploadip" => $ip,
            ));
        } else {
            $token = $this->escape($token);
            $ret = $this->update($infotable, array(
                "filename" => $filename,
                "time" => $now,
                "uploadip" => $ip,
            ), "token = $token");
        }
        return $ret;
    }
};


