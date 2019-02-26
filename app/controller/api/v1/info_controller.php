<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class info_controller extends v1_base {
    public function list_action() {
        $all = db_info::inst()->all();
        foreach ($all as $k => $v) {
            if (empty($v["filename"])) {
                $all[$k]["fileurl"] = "#";
            } else {
                $all[$k]["fileurl"] = UPLOAD_URL . $v["filename"];
            }
        }
        return array("op" => "info.list", "data" => $all);
    }

    public function message_action() {
        $token = get_session("info.token");
        $data = db_info::inst()->get_by_token($token);
        return array("op" => "info.message", "data" => $data);
    }

    public function update_action() {
        $token = get_session("info.token");
        $message = get_request_assert("message");
        $name = get_request_assert("name");
        $contact = get_request_assert("contact");
        $ret = db_info::inst()->update_message($token, $message, $name, $contact);
        return $this->checkRet($ret);
    }
}












