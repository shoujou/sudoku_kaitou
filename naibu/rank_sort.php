<?php 
 $file = file("rank.txt"); 
 foreach($file as $f){ 
	list($date, $name, $score,$q_number) = explode(',', rtrim($f)); 
	//選択された問題の場合のみ抽出
	if(strcmp($_POST['q_number'], $q_number) == 0){
		$db[$date.','.$name] = $score;
	}
 } 
 asort($db); //タイムが短い順にソートする 
 foreach($db as $key => $val) { 
 echo "$key,$val\n"; 
 } 
?>
