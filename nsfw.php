<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

header('Content-Type: application/json');
//if ($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']){
//    header("HTTP/1.1 401 Unauthorized");
//    echo(json_encode(['status' => '401', 'info' =>"No remote access allowed."]));
//    exit; //just for good measure
//}
$path = 'python/temp/';
$name = rand(1,9999999999);

function checkType($input){
    if($input != "jpg" && $input != "png" && $input != "jpeg") {
        return false;
    }else{
        return true;
    }
}
if (isset($_FILES['image'])){
    $imageExt = strtolower(pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false && checkType($imageExt) !== false) {
        $imagePath = $path.$name.".".$imageExt;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)){
            $arr = json_decode(str_replace("'", "\"", shell_exec("python python/nsfw_detector/predict.py --saved_model_path python/model/ --image_source ".$imagePath)), true);
            echo(json_encode($arr[$imagePath]));
            unlink($imagePath);
        }
    }else{
        header("HTTP/1.1 401 Unauthorized");
        echo(json_encode(['status' => '400', 'info' =>"Input didn't classify as images or supported image types."]));
        exit;
    }
}else{
    header("HTTP/1.1 401 Unauthorized");
    echo(json_encode(['status' => '401', 'info' =>"Didn't meet all required parameters!"]));
    exit;
}
