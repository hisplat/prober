<?php
include_once(dirname(__FILE__) . "/readfile.php");
$servername = “localhost”;//"114.215.82.75";
$username = "prober";
$password = "prober";
$dbname = "prober";

$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("Connection failed:".$conn->connect_error);
}
$sql1 = "SELECT id, filename FROM prober_info ORDER BY id";
$result1 = $conn->query($sql1);
$filenameMaps = new HashMap();
if($result1->num_rows > 0){
    while($row = $result1->fetch_assoc()){
        //echo "id:".$row["id"].$row["filename"]. "<br>";
        $infoid = $row["id"];
        echo $infoid. "<br>";
        $filename = $row["filename"];
        if(!empty($filename)){
            echo $filename. "<br>";
            //$filenameMaps->put($infoid,$filename);
            $sql2 = "SELECT * FROM prober_parsed WHERE infoid=".$infoid;
            $result2 = $conn->query($sql2);
            if($result2->num_rows == 0){
            $ret = ungzipfile($filename);
            if ($ret === false) {
                return "fail|解压文件失败.";
            }
            $sub = substr($filename, 0, 2);
            $dir = UPLOAD_DIR . $sub;
            $propfile = $dir . "/bug/properties";
            if (!file_exists($propfile)) {
                echo $propfile;
                die("要解压的文件不存在！");
            }
            list($productname, $branch, $fingerprint, $builddate,$softversion) = read_properties($propfile);
            $sqlinsert = "INSERT INTO prober_parsed (infoid,productname,branch,fingerprint,builddate,softversion) VALUES
            ('$infoid','$productname','$branch','$fingerprint','$builddate','$softversion')";
            try{
                $result3 = $conn->query($sqlinsert);
            }catch(Exception $e){
                echo $e;
            }
          }
        }
    }
    echo "done";
}else{
    echo "0 results find";
}
$conn->close();
?>



