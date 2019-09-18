<?php
include_once(dirname(__FILE__) . "/hashmap.php");
//include_once(dirname(__FILE__) . "/dbhelper.php");
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__FILE__) . "/..");
defined('UPLOAD_DIR') or define('UPLOAD_DIR', ROOT_PATH . '/upload/');

//$filename = "1f/1f500e8e76304b70356b4291fc7a94ff";
function ungzipfile($filename) {
    $sz = substr($filename, 0, 2);
    $filedir = UPLOAD_DIR . $sz;
    $filepath = UPLOAD_DIR . "$filename";
    if (!file_exists($filepath)) {
        echo $filepath . "要解压的文件不存在";
    }
    $pos = strripos($filename,'.'); //获取到文件名的位置
    $realfilename = substr($filename,0,$pos);
    $newfilepath = UPLOAD_DIR . "$realfilename" . ".tar.gz";
    rename("$filepath", "$newfilepath");
    if (!file_exists($newfilepath)) {
        echo $newfilepath;
        die("要解压的文件不存在！");
    }
    echo $newfilepath . "<br>";
    ob_end_clean();
    try {
        $phar = new PharData("$newfilepath");
        $phar->decompress();
    }
    catch(Exception $e) {
        echo $e;
    }
    echo "decompress done <br>";
    $tarfile = UPLOAD_DIR . $realfilename . ".tar";
    if (!file_exists($tarfile)) {
        echo $tarfile;
        die("要解压的文件不存在！");
    }
    $tar = new PharData("$tarfile");
    $tar->extractTo($filedir, 'bug/properties');
    echo "extract done <br>";
    $propfile = $filedir . "/bug/properties";
    chmod($propfile, 0755);
    //read_properties($propfile);
}
/*$sz = substr($filename, 0, 2);
$filedir = UPLOAD_DIR.$sz;
$filepath = UPLOAD_DIR . "$filename".".zip";
if (!file_exists($filepath)){
    echo $filepath."要解压的文件不存在";
}
$newfilepath = UPLOAD_DIR . "$filename".".tar.gz";
rename("$filepath","$newfilepath");
if (!file_exists($newfilepath)){
    echo $newfilepath;
    die ("要解压的文件不存在！");
}
echo $newfilepath. "<br>";
ob_end_clean();
try {
    $phar = new PharData("$newfilepath");
    $phar->decompress();
} catch (Exception $e) {
    echo $e;
}
echo "decompress done <br>";
$tarfile = UPLOAD_DIR .$filename.".tar";
if (!file_exists($tarfile)){
    echo $tarfile;
    die ("要解压的文件不存在！");
}
$tar = new PharData("$tarfile");
$tar->extractTo($filedir,'bug/properties');
echo "extract done";
$propfile = $filedir."/bug/properties";
chmod($propfile, 0755);
if (!file_exists($propfile)){
    echo $propfile;
    die ("要解压的文件不存在！");
}
read_properties($propfile);*/

//$phar->extractTo($filedir); // extract all files
//$phar->extractTo($filedir, 'bug/properties'); // extract only properties
/*$ret = unzipFile($filepath.".zip");
unrarFile($filename);*/
function unzipFile($file){
    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo(UPLOAD_DIR);
        $zip->close();
    }else {
        echo 'failed unzip file code:' . $res;
        return "fail|" . $res;
    }
    return "success";
}
function unrarFile($file){
    $rar_file = rar_open($file) or die("Can't open Rar archive");
    $rar_entry = rar_entry_get($rar_file,'bug/properties');
    $rar_entry->extract('../upload/');
    //$rar_entry->extract(false,"~/Downloads/properties.txt");
    rar_close($rar_file);
}
function read_properties($filename){
    $myfile = fopen($filename, "r+") or die("Unable to open file!");
    $properties = new HashMap();
    while(!feof($myfile)) {
        $strs = explode(":", fgets($myfile),2);
        $key = f4($strs[0]);
        $value = f4($strs[1]);
        $properties->put($key,$value);
        //echo $key.":";
        //echo $properties->get($key). "<br>";
    }
    fclose($myfile);
    $fingerprint = $properties->get("ro.build.fingerprint");
    echo($fingerprint. "<br>");
    $product = $properties->get("ro.product.board");
    echo($product."<br>");
    $productname = $properties->get("ro.product.hisense.model");
    echo($productname."<br>");
    $branch = $properties->get("ro.product.hisense.branch");
    $builddate = $properties->get("ro.build.date.utc"). "<br>";
    echo($builddate)."<br>";
    $softversion = $properties->get("ro.build.hisense.softversion");
    echo($softversion);
    return array(0 => $productname, 1 => $branch, 2 => $fingerprint, 3 => $builddate,4 => $softversion);
}
  //TODO branch
  /**
   * 
   */
  function f4($str) { 
      $result = array(); 
      preg_match_all("/(?:\[)(.*)(?:\])/i",$str, $result); 
      return $result[1][0]; 
    }
?>