// 投稿された内容を取得するSQLを作成して結果を取得
$sql = "select * from `post` order by `create_at` desc";
$result = mysql_query($sql, $link);

// 取得した結果を$postsに格納
$posts = array();
if ($result !== false && mysql_fetch_assoc($result)) {
  while ($post = mysql_fetch_assoc($result)) {
    $posts[] = $post;
  }
}
