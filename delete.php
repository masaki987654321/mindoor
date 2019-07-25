<?php
  require_once('./functions.php');
  session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST"){
  $pdo = connectDB();

  $sql = "DELETE FROM articles where id = :article_id";
  $statement = $pdo->prepare($sql);
    $statement->execute([
      ':article_id' => $_POST['article_id'],
    ]);
  header ("location: mypage.php");
}

?>
