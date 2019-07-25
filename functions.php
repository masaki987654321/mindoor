<?php

// DB接続
// 引数なし関数を生成
function connectDB()
{
	require_once('./config.php');
	// データソース名を変数に
	$dsn = "mysql:dbname=$db_name;host=$host";

	try {
	// $pdo = new PDO(データソース名, ユーザー名, パスワード);
		$pdo = new PDO($dsn, $db_user, $db_passwd);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	} catch (PDOException $e) {
		die('DB Connection Faild');
	// メッセージを出力し、終了
	}
	return $pdo;
	// この関数は　$pdo　を返す
}

// ユーザーから入力された文字を安全な文字列に変換する関数(HTMLエスケープ)
function h($string) {
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// ログインしていなかったらログインページへリダイレクトする
function redirectIfNotLogin()
{
    // ログインしてなかったら
    if (!isset($_SESSION['user'])) {
        // ログインページヘリダイレクトする
				// header('Location: URL'); 	指定したURLのブラウザを表示する
        header('Location: login.php');
        return;
    }
}

// ログインしているユーザーの情報を取得する
function loginUser()
{
    return $_SESSION['user'];
}
