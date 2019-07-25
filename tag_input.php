<?php
  require_once('./functions.php');
    $pdo = connectDB();
    // gradetags から持ってくる
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

  if ($_SERVER["REQUEST_METHOD"] === "POST") {

// インサート
    $sql = "INSERT INTO gradetags (name)
     VALUES (:name)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
      ':name' => $_POST['gradetags']
    ]);

    $sql = "INSERT INTO subjecttags (name)
     VALUES (:name)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
      ':name' => $_POST['subjecttags']
    ]);

    $sql = "INSERT INTO junretags (name,gradetags,subjecttags)
     VALUES (:name,:gradetags,:subjecttags)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
      ':name' => $_POST['junretags'],
      ':gradetags' => $_POST['gradetags_select'],
      ':subjecttags' => $_POST['subjecttags_select']
    ]);
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>新規タグ登録</title>
	<link rel="stylesheet" href="./my.css">
</head>
<body>
	<div id="all">
    <h2>新規タグ登録</h2>
        <form action="" method="post">
          <p>学年：<textarea name="gradetags" rows="5" cols="40"></textarea></p>
          <input type="submit" value="送信">
        </form>

        <form action="" method="post">
          <p>科目：<textarea name="subjecttags" rows="5" cols="40"></textarea></p>
          <input type="submit" value="送信">
        </form>

        <form action="" method="post">
          <?php foreach($gradetags as $gradetags): ?>
          <input type="radio" name="gradetags_select" value="<?php echo $gradetags['name']; ?>"><?php echo $gradetags['name'];?>
          <?php endforeach; ?>
          <br>
          <?php foreach($subjecttags as $subjecttags): ?>
          <input type="radio" name="subjecttags_select" value="<?php echo $subjecttags['name']; ?>"><?php echo $subjecttags['name'];?>
          <?php endforeach; ?>
          <br>
          <p>ジャンル：<textarea name="junretags" rows="5" cols="40"></textarea></p>
          <input type="submit" value="送信">
        </form>
	</div>

</body>
</html>
