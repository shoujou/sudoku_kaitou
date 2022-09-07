<!DOCTYPE html> 
<html lang="ja"> 
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
<meta charset="UTF-8"/>
</head>
<body>
<a href = np3.php><p>出題ページへ戻る</p></a>
<?php echo "<p>問題No.".$_POST['q_number']."のランキング</p>";  ?>
<div id='ranking'></div>
<script>
var q_num = '<?php echo $_POST['q_number']; ?>';
var str_qnum = String(q_num);
	$.ajax({
		url: 'rank_sort.php',
                type: 'POST',
                data: {'q_number': str_qnum}
        }).done(function(data){
      		var lines = data.split("\n");
                var table = "<table border=1 cellspacing=0 cellpadding=2>";
		table += "<tr><td>順位</td><td>タイム</td><td>ユーザー名</td><td>日時</td><tr>";
		for(let i=0; i < lines.length-1; i++){
			var line = lines[i].split(",");
			table +=  "<tr><td>"+ (i+1) + "</td><td>" + line[2] + "</td><td>" + line[1] + "</td><td>" + line[0] + "</td></tr>";
		}
		table += "</table>";
		document.getElementById('ranking').innerHTML = table;
	});


</script>
</body>
</html>
