<?php
  require_once('./functions.php');
  session_start();

  // ログインしていなかったら、ログイン画面にリダイレクトする
  redirectIfNotLogin(); // ※ この関数はfunctions.phpに定義してある
  $id = $_SESSION['user']['id'];
  $username = $_SESSION['user']['username'];

  $pdo = connectDB();

  $sql = "SELECT * FROM articles where user_id = :id order by created_at desc" ;
  $statement = $pdo->prepare($sql);
    $statement->execute([
      ':id' => $id,
    ]);
  $articles = $statement->fetchAll();



?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>mindoor</title>

  <link rel="stylesheet" href="./my.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- jQuery読み込み -->
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
  <!-- lightbox2読み込み -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
</head>
<body>
<!-- popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <!-- boostrap js -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <div id="all">

  <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#bs-navi" aria-controls="bs-navi" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="logo">
      <p><a href="./homepage.php">mindoor</p>
    </div>
    <div class="collapse navbar-collapse" id="bs-navi">
      <ul class="navbar-nav w-100 nav-justified">
        <li class="nav-item"><a href="./homepage.php">ホーム</li>
        <li class="nav-item"><a href="./mypage.php">マイページ</a></li>
        <li class="nav-item"><a href="./new_article.php">新規投稿</a></li>
        <li class="nav-item"><a href="./logout.php">ログアウト</a></li>
      </ul>
    </div>
  </nav>

<!-- naviberとかぶらないようにするために、空白を作る -->
  <div class="header_down">
    <p></p>
  </div>

 <!-- 投稿内容表示 -->
 <div class="container">
  <div class="row">
   <?php foreach($articles as $articles): ?>
     <div class="col-lg-6 col-lg-4">
      <div class="box">
         <a href="pictures/<?php echo $articles['picture'];?>" data-lightbox="abc">
           <image src = "pictures/<?php echo $articles['picture'];?>"class = "pictures" width="100%">
         </a>
         <p><?php echo $articles['comment'];?></p>
<!-- 削除フォーム -->
         <form action="delete.php" method="post">
           <input type="hidden" name="article_id" value="<?php echo $articles['id'];?>"><br>
           <input type="submit" value="削除"><br>
          </form>
        </div>
      </div>
   <?php endforeach; ?>
 </div>
</div>

</div>
<!-- all -->
</body>
</html>
