<?php

// include_once(dirname(__FILE__) . "/../config.php");

function uploadImageViaFileReader($imgsrc = null, $callback = null, $args = null) {
    $whitelist = array("image/jpeg", "image/pjpeg", "image/png", "image/x-png", "image/gif");

    if ($imgsrc == null) {
        $imgsrc = get_request_assert("imgsrc");
    } else if (substr($imgsrc, 0, 5) != "data:") {
        $imgsrc = get_request_assert($imgsrc);
    }

    // data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQIBAQICAgICAgIC…gAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z
    $arr = explode(";", $imgsrc);
    if (count($arr) != 2) {
        return "fail|数据错误.";
    }

    $arr1 = explode(":", $arr[0]);
    if (count($arr1) != 2) {
        return "fail|数据错误..";
    }
    $type = $arr1[1];
    if (!in_array($type, $whitelist)) {
        return "fail|不支持的文件格式: $type.";
    }

    $type = explode('/', $type);
    $extension = $type[1];

    $arr = explode('base64,', $imgsrc);
    $image_content = base64_decode($arr[1]);

    if (!file_exists(UPLOAD_DIR)) {
        $ret = @mkdir(UPLOAD_DIR, 0777, true);
        if ($ret === false) {
            return "fail|上传目录创建失败.";
        }
    }

    $filename = md5($image_content) . ".$extension";

    $filepath = UPLOAD_DIR . "/$filename";
    if (!file_put_contents($filepath, $image_content)) {
        return 'fail|创建文件失败.';
    }
    if ($callback != null) {
        return $callback($filename, $args);
    }
    return "success";
}


function uploadFile($token, $callback = null, $args = null) {
    // logging::d("upload", $_FILES);
    // logging::d("uploadDebug", $c);

    // if (!isset($_FILES["file"]["tmp_name"])) {
    //     return "fail|" . $_FILES["file"]["error"];
    // }

    $c = file_get_contents("php://input");

    $token = md5($token);

    $sz = substr($token, 0, 2);
    $uploaddir = UPLOAD_DIR . "/$sz";

    // logging::d("Debug", ROOT_PATH);
    logging::d("Debug", $uploaddir);
    if (!file_exists($uploaddir)) {
        $ret = @mkdir($uploaddir, 0777, true);
        if ($ret === false) {
            return "fail|上传目录创建失败.";
        }
    }

    $filename = $token . ".tgz";
    $filepath = $uploaddir . "/$filename";

    if (!file_put_contents($filepath, $c)) {
        return "fail|创建文件失败.";
    }

    // $ret = move_uploaded_file($_FILES["file"]["tmp_name"], $filepath);
    // if (!$ret) {
    //     return "fail|移动文件失败.";
    // }

    if ($callback != null) {
        return $callback("$sz/$filename", $args);
    }

    return "success";
}

