<?php
require_once 'inc/headers.php';

$input = json_decode(file_get_contents('php://input'));
$description = filter_var($input->description,FILTER_SANITIZE_STRING);

try {
  $db = new PDO('mysql:host=localhost;port=8889;dbname=todo;charset=utf8','root','root');
  $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  
  $query = $db->prepare('insert into task(description) values (:description)');
  $query->bindValue(':description',$description,PDO::PARAM_STR);
  $query->execute();

  echo header('HTTP/1.1 200 OK');
  $data = array('id' => $db->lastInsertId(),'description' => $description);
  echo json_encode($data);
}
catch (PDOException $pdoex) {
  echo header('HTTP/1.1 500 Internal Server Error');
  $error = array('error' => $pdoex->getMessage());
  echo json_encode($error);
  exit;
}

// Preflight... https://stackoverflow.com/questions/44479681/cors-php-response-to-preflight-request-doesnt-pass-am-allowing-origin