<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    $user = trim(filter_input(INPUT_POST, 'user'));
    $pass = trim(filter_input(INPUT_POST, 'pass'));
    if (!empty($user) && !empty($pass)) {
        $fp = fopen('naibu/.htpasswd', 'r+');
        flock($fp, LOCK_EX);
        while ($dat = fgetcsv($fp, 1024, ":")) {
            if (count($dat) !== 2) continue;
            if ($dat[0] !== $user) continue;
            flock($fp, LOCK_UN);
            die("user already exists");
        }
        fwrite($fp, "$user:".password_hash($pass,PASSWORD_DEFAULT)."\n");
//        flock($fp, LOCK_UN);  この行は省略すべし
        fclose($fp);
        echo "名前:"."\n".$user."\n"."パスワード:"."\n".$pass."<br/>";
    } else die('empty');
?>
<br>
<p>登録完了しました</p><br><br><br>
<a href="naibu/np3.php">ゲーム画面へログインする</a>
</body>
</html>
