<?php
require_once 'inc/headers.php';

$input = json_decode(file_get_contents('php://input'));
$id = filter_var($input->id,FILTER_SANITIZE_NUMBER_INT);

try {
  $db = new PDO('mysql:host=localhost;port=8889;dbname=todo;charset=utf8','root','root');
  $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  
  $query = $db->prepare('delete from task where id=(:id)');
  $query->bindValue(':id',$id,PDO::PARAM_INT);
  $query->execute();

  echo header('HTTP/1.1 200 OK');
  $data = array('id' => $id);
  echo json_encode($data);
}
catch (PDOException $pdoex) {
  echo header('HTTP/1.1 500 Internal Server Error');
  $error = array('error' => $pdoex->getMessage());
  echo json_encode($error);
  exit;
}

// Preflight... https://stackoverflow.com/questions/44479681/cors-php-response-to-preflight-request-doesnt-pass-am-allowing-origin