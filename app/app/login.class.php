<?php

include_once(dirname(__FILE__) . "/../config.php");

class Login {
    public static function assert_login() {
        if (isset($_SESSION["user.id"]) && isset($_SESSION["user.email"]) && isset($_SESSION["user.nick"]) && isset($_SESSION["user.face"])) {
            return;
        }
        self::do_login();
    }

    public static function do_login() {
        $jiraauth = (int)setting::instance()->load("KEY_JIRA_AUTH");

        if ($jiraauth == 0) {
            $comaccui = setting::instance()->load("KEY_COMACC_UI");
            $url = HOME_URL . "?action=index.auth";
            $url = urlencode($url);
            $url = $comaccui . "&next=$url&title=绩效考评";
            header("Location: $url");
            die("");
        } else if ($jiraauth == 1) {
            go("index/jiralogin");
        } else {
            die("AuTH_TYPE not set.");
        }
    }

    public static function do_auth() {
        $jiraauth = (int)setting::instance()->load("KEY_JIRA_AUTH");
        if ($jiraauth == 0) {
            $userid = get_request_assert("userid");
            $token = get_request_assert("token");
            $comaccauth = setting::instance()->load("KEY_COMACC_AUTH");
            $url = $comaccauth . "&userid=$userid&token=$token";
            $c = file_get_contents($url);
            $c = json_decode($c, true);
            // dump_var($c);
            if (isset($c["id"]) && isset($c["ret"]) && $c["ret"] == "success") {
                $user = db_user::inst()->refresh($c["email"], $c["nick"], $c["face"]);
                $userid = $user["userid"];
                $_SESSION["user.id"] = $userid;
                $_SESSION["user.email"] = $c["email"];
                $_SESSION["user.nick"] = $c["nick"];
                $_SESSION["user.face"] = $c["face"];
                $_SESSION["user.admin"] = $user["admin"];
            } else {
                die("Login failed.");
            }
        } else if ($jiraauth == 1) {
            $username = get_request_assert("username");
            $password = get_request_assert("password");
            $jiraurl = setting::instance()->load("KEY_JIRA_URL");
            $j = new JiraAuth($jiraurl);
            $ret = $j->login($username, $password);
            if (!$ret) {
                return null;
            }
            $user = db_user::inst()->refresh($j->getEmail(), $j->getNick(), $j->getAvatar());
            $userid = $user["userid"];
            $_SESSION["user.id"] = $userid;
            $_SESSION["user.email"] = $j->getEmail();
            $_SESSION["user.nick"] = $j->getNick();
            $_SESSION["user.face"] = $j->getAvatar();
            $_SESSION["user.admin"] = $user["admin"];
            return $userid;
        } else {
            die("AuTH_TYPE not set.");
        }
    }

    public static function nick() {
        if (isset($_SESSION["user.nick"])) {
            return $_SESSION["user.nick"];
        }
        return "";
    }

    public static function userid() {
        return get_session("user.id", 0);
    }

    public static function logout() {
        unset($_SESSION["user.id"]);
        unset($_SESSION["user.email"]);
        unset($_SESSION["user.nick"]);
        unset($_SESSION["user.face"]);
        unset($_SESSION["user.admin"]);
    }

    public static function is_admin() {
        $admin = get_session("user.admin");
        return ($admin == 1);
    }

    public static function assert_admin() {
        if (self::is_admin()) {
            return;
        }
        die("You are not admin.");
    }

    public static function avatar() {
        $url = get_session("user.face", "");
        return $url;
    }
};


