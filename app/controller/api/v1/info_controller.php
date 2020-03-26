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
            $all[$k]["time"] = Date("Y-m-d H:i:s", $v["time"]);
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

    public function updatecomment_action() {
        $id = get_request_assert("id");
        $comment = get_request_assert("comment");
        $ret = db_info::inst()->update_comment($id, $comment);
        return $this->checkRet($ret);
    }

    public function unparsed_action() {
        $files = db_info::inst()->get_unparsed_files();
        return $files;
    }

    public function updateparsed_action() {
        $id = get_request_assert("id");
        $product = get_request_assert("product");
        $version = get_request_assert("version");
        $builddate = get_request_assert("builddate");
        $device = get_request_assert("device");
        $fingerprint = get_request_assert("fingerprint");

        $ret = db_info::inst()->update_parsed($id, $product, $fingerprint, $builddate, $version, $device);
        return $this->checkRet($ret);
    }

    public function remove_action() {
        $id = get_request_assert("id");
        $one = db_info::inst()->get_by_id($id);
        $filename = $one["filename"];
        if (!empty($filename)) {
            $filename = UPLOAD_DIR . "/$filename";
            unlink($filename);
            logging::d("Remove", "unlink $filename.");
        }
        $ret = db_info::inst()->remove_record($id);
        return $this->checkRet($ret);
    }

    public function listdeprecated_action() {
        $data = db_info::inst()->list_deprecated();
        return $data;
    }
}












