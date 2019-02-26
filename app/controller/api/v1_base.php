<?php
include_once(dirname(__FILE__) . "/../../config.php");

class v1_base {
    const kRet_Success = 0;
    const kRet_Fail = 1;
    const kRet_NoSuchParser = 2;
    const kRet_NotLogin = 3;

    protected function packArray($arr) {
        $data = array();
        foreach ($arr as $record) {
            $data[] = $record->packInfo();
        }
        return $data;
    }

    protected function result($code) {
        $table = array(
            self::kRet_Success => "success",
            self::kRet_Fail => "fail",
            self::kRet_NoSuchParser => "No such parser",
            self::kRet_NotLogin => "Not login",
        );
        $reason = isset($table[$code]) ? $table[$code] : $code;
        return array("op" => "result", "data" => array("code" => $code, "reason" => $reason));
    }

    protected function op($op, $data) {
        return array("op" => $op, "data" => $data);
    }

    protected function checkRet($ret) {
        return $this->result(($ret !== false) ? self::kRet_Success : self::kRet_Fail);
    }

}













