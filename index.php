<?php
  require_once('./functions.php');
  session_start();
  // DB接続
  $pdo = connectDB(); // ※ この関数はfunctions.phpに定義してある
// 択選択フォームからのpost
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $gradetags_select = $_POST['gradetags'];
  $subjecttags_select = $_POST['subjecttags'];
  $junretags_select = $_POST['junretags'];

  if (empty($gradetags_select) || empty($subjecttags_select) || empty($junretags_select)){
    $_SESSION["error"] = "選択されてない項目があります";
    header("Location: index.php");
    return;
  }
}
 // articles データベースから持ってくる
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $sql = "SELECT * FROM articles WHERE gradetag = :gradetags_select AND subjecttag = :subjecttags_select AND junretag = :junretags_select order by created_at desc";
 }else {
   $sql = "SELECT * FROM articles order by created_at desc";
 }
   $statement = $pdo->prepare($sql);
     $statement->execute([
       ':gradetags_select'=>$gradetags_select,
       ':subjecttags_select'=>$subjecttags_select,
       ':junretags_select'=>$junretags_select,
     ]);
   $articles = $statement->fetchAll();


$sql = "SELECT * FROM gradetags" ;
$statement = $pdo->prepare($sql);
  $statement->execute([
    ':name' => $grade_name,
  ]);
$gradetags = $statement->fetchAll();

// subjecttags データベースから持ってくる
  $sql = "SELECT * FROM subjecttags" ;
  $statement = $pdo->prepare($sql);
    $statement->execute([
      ':name' => $subject_name,
    ]);
  $subjecttags = $statement->fetchAll();

  // junretags データベースから持ってくる
    $sql = "SELECT * FROM junretags" ;
    $statement = $pdo->prepare($sql);
      $statement->execute([
        ':name' => $junre_name,
      ]);
    $junretags = $statement->fetchAll();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" >
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
      <p><a href="">mindoor</p>
    </div>
    <div class="collapse navbar-collapse" id="bs-navi">
      <ul class="navbar-nav w-100 nav-justified">
        <li class="nav-item"><a href="">ホーム</li>
        <li class="nav-item"><a href="./login.php">ログイン</a></li>
      </ul>
    </div>
  </nav>

<!-- naviberとかぶらないようにするために、空白を作る -->
  <div class="header_down">
    <p></p>
  </div>
  
<!-- タグ選択フォーム -->
<form action="" method="post">

  <div class="tags">
    <ul>
      <li>学年</li>
      <li>
        <select name="gradetags" id="gradetags" onclick="selectboxChange();">
          <option value="undefined" selected>---</option>
          <?php foreach($gradetags as $grade_row): ?>
            <option  class="gradetag" value="<?php echo $grade_row['name']; ?>"><?php echo $grade_row['name'];?></option>
          <?php endforeach; ?>
        </select>
        </li>
      </li>
    </ul>
    <ul>
      <li>教科</li>
      <li>
      <select name="subjecttags" id="subjecttags" onclick="selectboxChange();">
        <option value="undefined" selected>---</option>
        <?php foreach($subjecttags as $subject_row): ?>
          <option class="subjecttag "value="<?php echo $subject_row['name']; ?>"><?php echo $subject_row['name'];?></option>
        <?php endforeach; ?>
      </select>
     </li>
   </ul>
   <ul>
    <li>ジャンル</li>
    <li>
      <select name="junretags" >
        <script>
        <!-- 選択したものを変数に入れる -->
          var gradetags_select;
          $('#gradetags').change(function() {
              gradetags_select = $("option.gradetag:selected").val();
          });
          var subjecttags_select;
          $('#subjecttags').change(function() {
            subjecttags_select = $("option.subjecttag:selected").val();
          });
// ジャンルタグ生成関数
          function selectboxChange() {
            var junres=<?php
                echo json_encode($junretags);
            ?>;
            var junreElement = $('[name=junretags]')
            junreElement.empty()
            junres.forEach(junre => {
              if (junre.gradetags == gradetags_select && junre.subjecttags == subjecttags_select){
              junreElement.append("<option value='" + junre['name'] + "'>" + junre['name'] + "</option>")
              }
            });
          }
          // selectboxChange終了
        </script>
      </select>
    </li>
  </ul>
    <input type="submit" value="決定">
  </div>
  <!-- tag -->

</form>
<!-- 投稿内容表示 -->
<div class="container">
  <div class="row">
  <?php foreach($articles as $articles):?>
    <div class="col-lg-6 col-lg-4">
      <div class="box">
        <a href="pictures/<?php echo $articles['picture'];?>" data-lightbox="abc">
          <img src = "pictures/<?php echo $articles['picture'];?>" width="100%">
        </a>
        <p><?php echo $articles['comment'];?></p>
      </div>
    </div>
   <?php endforeach; ?>
  </div>
</div>

<!-- all -->
</div>

</body>
</html>
