<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>問題登録確認</title>
</head>
<body>

<?php	
	$numberfile = '1.txt';
	$answerfile = '1_a.txt';
	$number = $_POST['send_num'];
	$answer = $_POST['send_ans'];
	$cnt = 0;
	$flag = 0;

	//echo $number."\n";
	//echo $answer."\n";
	//問題の重複チェック
	$fp = fopen("1.txt","r");
	if($fp){
		while ($line = fgets($fp)){
			$line = str_replace(PHP_EOL,'',$line);
			if(strcmp($line,$number) == 0){
				$flag = 1;
			}
			$cnt++;	
		}
	}
	fclose($fp);
	$cnt++;

	if($flag){
		echo "<p>既に同じ問題が登録されています</p>";	
	}
	else {
		$fp = fopen('1.txt', 'a');
		if($fp){
			fwrite($fp, $number."\n");
		}
		fclose($fp);

		$fp = fopen('1_a.txt', 'a');
		if($fp){
			fwrite($fp, $answer."\n");
		}
		fclose($fp);
		
		echo "<p>問題No.".$cnt."として登録しました</p>";
	}
?>
<a href="syutudai.html"><p>作問ページへ戻る</p></a>
</body>
