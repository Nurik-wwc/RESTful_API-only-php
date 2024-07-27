<?php

function getPosts($connect)
{
  $posts = mysqli_query($connect, "SELECT * FROM `posts`");
  $postsList = [];
  while ($post = mysqli_fetch_assoc($posts)) {
    $postsList[] = $post;
  }
  echo json_encode($postsList);
}

function getPost($connect, $id)
{
  $post = mysqli_query($connect, "SELECT * FROM `posts` WHERE `id` = '$id'");

  if (mysqli_num_rows($post) === 0) {
    http_response_code(404);
    $res = [
      "status" => false,
      "message" => "Post not found"
    ];
    echo json_encode($res);
  } else {
    $post = mysqli_fetch_assoc($post);
    echo json_encode($post);
  }
}

function addPost($connect, $data)
{
  $title = isset($data['title']) ? $data['title'] : '';
  $content = isset($data['content']) ? $data['content'] : '';

  if (empty($title) || empty($content)) {
    echo json_encode(['error' => 'Title and content are required']);
    return;
  }

  $stmt = $connect->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
  if ($stmt === false) {
    echo json_encode(['error' => $connect->error]);
    return;
  }
  $stmt->bind_param("ss", $title, $content);

  if ($stmt->execute()) {
    echo json_encode(['success' => 'Post created successfully', 'id' => $connect->insert_id]);
  } else {
    echo json_encode(['error' => $stmt->error]);
  }

  $stmt->close();
}

function updatePost($connect, $id, $data)
{
  // Получение данных
  $title = mysqli_real_escape_string($connect, $data['title']);
  $content = mysqli_real_escape_string($connect, $data['content']);

  // Формирование запроса
  $query = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $id";

  // Выполнение запроса
  if (mysqli_query($connect, $query)) {
    http_response_code(200);
    $res = [
      "status" => true,
      "message" => "Post is updated"
    ];
  } else {
    http_response_code(500); // Internal Server Error
    $res = [
      "status" => false,
      "message" => "Failed to update post: " . mysqli_error($connect)
    ];
  }
  // Отправка ответа
  echo json_encode($res);
}

function deletePost($connect, $id) {
  mysqli_query($connect,"DELETE FROM posts WHERE `posts`.`id` = '$id'");
  http_response_code(200);
    $res = [
      "status" => true,
      "message" => "Post is deleted"
    ];
}