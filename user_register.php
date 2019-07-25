<?php
  require_once('./functions.php');
  session_start();


  // POSTリクエストの場合
  if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 送られた値を変数に格納
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $passwd_confirmation = $_POST['passwd_confirmation'];
    $nichiji = date('Y-m-d H:i:s');

    // 未入力の項目があるか
    if (empty($username) || empty($passwd) || empty($passwd_confirmation)) {
        $_SESSION["error"] = "入力されていない項目があります";
        header("Location: user_register.php");
        return;
    }

    // パスワードとパスワード確認が一致しているか
    if ($passwd !== $passwd_confirmation) {
        $_SESSION["error"] = "パスワードが一致しません";
        header("Location: user_register.php");
        return;
    }
    $pdo = connectDB();

    $sql = "INSERT INTO users (username, passwd,created_at,modified_at) VALUES (:username, :passwd,:created_at,:modified_at)";
      $statement = $pdo->prepare($sql);
      $result = $statement->execute([
        ':username' => $username,
        ':passwd' => crypt($passwd),
        ':created_at' => $nichiji,
        ':modified_at' => $nichiji,
      ]);


    if (!$result) {
        die('Database Error');
    }

    // セッションにメッセージを格納
    $_SESSION["success"] = "登録が完了しました。ログインしてください。";
    // ログイン画面に遷移
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>ユーザー登録</title>
	<link rel="stylesheet" href="./my.css">
</head>
<body>
	<div id="all">

    <!-- セッション変数(successやerror)に値が入っている場合の処理
    ログインに成功した．または失敗した理由を表示 -->
      <!-- Success Message -->
      <?php if(!empty($_SESSION['success'])): ?>
          <div class="alert alert-success" role="success">
            <!-- メッセージを表示 -->
              <pre><?php echo $_SESSION['success']; ?></pre>
            <!-- セッション変数 succcess の値を空に -->
              <?php $_SESSION['success'] = null; ?>
          </div>
      <?php endif; ?>
      <!-- Error Message -->
      <?php if(!empty($_SESSION['error'])): ?>
          <div>
            <!-- メッセージを表示 -->
              <pre><?php echo $_SESSION['error']; ?></pre>
            <!-- セッション変数 succcess の値を空に -->
              <?php $_SESSION['error'] = null; ?>
          </div>
      <?php endif; ?>
      <div class="login">
        <h1>新規ユーザー登録</h1>

        <form action="" method="post">
          <div class="form-item">
            <label for="username-input">ユーザー名</label>
            <input type="text" name="username" placeholder="">
          </div>
          <div class="form-item">
            <label for="password-input">パスワード</label>
            <input type="password" name="passwd" placeholder="">
          </div>
          <div class="form-item">
            <label for="password-input">パスワード再入力</label>
            <input type="password" name="passwd_confirmation" placeholder="">
          </div>
          <div class="button-panel">
            <input type="submit" class="button" title="登録" value="登録"></input>
          </div>
        </form>

      </div>

</body>
</html>
