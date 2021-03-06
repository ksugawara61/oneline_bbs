<?php
// データベースに接続
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
	die('データベースに接続できません: ' . mysqk_error());
}

// データベースを選択
mysql_select_db('oneline_bbs', $link);

$errors = array();

// POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 名前が正しく入力されているかチェック
	$name = null;
	if (!isset($_POST['name']) || !strlen($_POST['name'])) {
		$errors['name'] = '名前を入力してください';
	} else if (strlen($_POST['name']) > 40) {
		$errors['name'] = '名前は40字以内で入力してください';
	} else {
		$name = $_POST['name'];
	}

	// ひとことが正しく入力されているかチェック
	$comment = null;
	if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
		$errors['comment'] = 'ひとことを入力してください';
	} else if (strlen($_POST['comment']) > 200) {
		$errors['comment'] = 'ひとことは200文字以内で入力してください';
	} else {
		$comment = $_POST['comment'];
	}

	// エラーがなければ保存
	if (count($errors) === 0) {
		// 保存するためのSQL文を作成
		$sql = "insert into `post` (`name`, `comment`, `create_at`) values ('"
			. mysql_real_escape_string($name) . "', '"
			. mysql_real_escape_string($comment) . "', '"
			. date('Y-m-d H:i:s') . "')";

		// 保存する
		mysql_query($sql, $link);

		mysql_close($link);
		header('Location: http://' .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
}

// 投稿された内容を取得するSQLを作成して結果を取得
$sql = "select * from `post` order by `create_at` desc";
$result = mysql_query($sql, $link);

// 取得した結果を$postsに格納
$posts = array();
if ($result !== false && mysql_num_rows($result)) {
	while ($post = mysql_fetch_assoc($result)) {
		$posts[] = $post;
	}
}

// 取得結果を開放して接続を閉じる
mysql_free_result($result);
mysql_close($link);

include 'views/bbs_view.php';
?>
