<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

require_once 'connect.php';
require_once 'functions.php';

$connect = new mysqli($host, $user, $password, $database);
if ($connect->connect_error) {
  die('Ошибка подключения: ' . $connect->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];
$q = isset($_GET['q']) ? $_GET['q'] : '';
$params = explode('/', $q);

$type = isset($params[0]) ? $params[0] : '';
$id = isset($params[1]) ? $params[1] : '';

if ($method === 'GET') {
  if ($type === 'posts') {
    if ($id) {
      getPost($connect, $id);
    } else {
      getPosts($connect);
    }
  }
} elseif ($method === 'POST') {
  if ($type === 'posts') {
    addPost($connect, $_POST);
  }
} elseif ($method === 'PATCH') {
  if ($type === 'posts') {
    if (isset($id)) {
      $data = file_get_contents('php://input');
      $data = json_decode($data, true);
      updatePost($connect, $id, $data);
    }
  }
} elseif ($method === 'DELETE') {
  if ($type === 'posts') {
    if (isset($id)) {
      deletePost($connect, $id);
    }
  }
}

$connect->close();
