<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include "https://raw.githubusercontent.com/Rakiden/api_prueba/master/server_api/config/config.php";

$postjson = json_decode(file_get_contents('php://input'),true);
$today = date('Y-m-d');

if($postjson['aksi']=="register"){
    $password = md5($postjson['password']);
    $query = mysqli_query($mysqli, "INSERT INTO master_user SET
        username = '$postjson[username]',
        password = '$password',
        status   = 'y',
        created_at = '$today'
    ");

    if($query) $result = json_encode(array('success'=>true));
    else $result = json_encode(array('success'=>false, 'msg'=>'error, please try again'));

    echo $result;
  }
  elseif($postjson['aksi']=="login"){
    $password = md5($postjson['password']);
    $query = mysqli_query($mysqli, "SELECT * FROM master_user WHERE username='$postjson[username]' AND password = '$password'");
    $check = mysqli_num_rows($query);

    if($check>0){
        $data = mysqli_fetch_array($query);
        $datauser = array(
            'user_id' =>$data['user_id'], 
            'username' =>$data['username'], 
            'password' =>$data['password']
        );

        if($data['status']=='y'){
            $result = json_encode(array('success'=>true,'result' => $datauser));
        }else{
            $result = json_encode(array('success'=>false,'mgs' => "Account Inactive"));
        }
    }else{
        $result = json_encode(array('success'=>false,'mgs' => "'$postjson[username]' password = '$password'"));
    }

    echo $result;
  }
?>