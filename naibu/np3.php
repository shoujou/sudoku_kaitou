<?php 
date_default_timezone_set('Asia/Tokyo');
$username = $_SERVER['PHP_AUTH_USER'];
$today = date("y/m/d H:i:s");
?>
<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
 		<title>ナンプレ</title>
		<link rel="prev" href="login.html">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		
    </head>
    	<body>

	<table border="1" cellpadding=0 cellspacing=0>
	<a href="syutudai.html">問題作成ページ</a>
		<h3>ルール&マニュアル</h3>
		<tr><th align = "left">
			<h5>
			<span style="color:red">ナンプレのルール</span><br>
			どのタテ列にも1～9の数字が1個ずつ入る。<br>
			どのヨコ列にも1～9の数字が1個ずつ入る。<br>
			どの３×３ブロックにも1～9の数字が1個ずつ入る。<br>
			<br>
			<span style="color:red">マニュアル</span><br>
			キーボードの矢印キーで移動きます。<br>
			入力したい数字もキーボードのナンバーから入力してください。<br>
			青色の数字が元から記載されている数字です。<br>
			赤色の数字が入力した数字です。<br>
			</h5>
		</th>
		</tr>
	</table>

	<?php
	$game_User ="名前".":".$username; 
	echo $today."<br>";
	echo $game_User;
	?>

	<br><br>


	<select name="q_number" id="q_num">
		<option disabled selected>問題No.</option>
	</select>
	<input type="button" onClick="game_start()" value="ゲーム開始" id="startcount">
	<input type="button" onClick="check()" value="答え合わせ" id="answer">
	<input type="button" onClick="giveup()" value="ギブアップ"><br>

	タイム:<p id="PassageArea">(ここに時間が表示されます)</p><br>
	<span id="msg">数字を入力して完成させてみよう</span><br>
	<br>
	<canvas width="450" height="450" id="canvas" style="border:1px solid black"></canvas>
	<br>
	<div id="ranking_num"></div>
	<div id="ranking"></div>
	<script type="text/javascript">
		var nowTime = new Date();//現在日時を得る。
		var nowSec = nowTime.getSeconds();//秒を抜き出す。
		var PassSec;    //秒数カウント用変数
		var st=null;    //開始時刻
		var en=null;    //終了時刻		
		var tid=0;  //開始状態かそれ以外か、(開始時は1それ以外は0)
		var a1 = new Array(8);	//　移動した時の効果音を入れる配列
//		var a2 = new Audio("kettei.mp3");	//　入力字の音
		var a2 = new Array(4);	//　入力字の音
		var a3 = new Audio("end.mp3");	//　完成時の音
		var a1_cnt = 0;	//　次に効果音を鳴らす配列要素の値
		var a2_cnt = 0;	//　次に効果音を鳴らす配列要素の値
		var q_src = "1.txt";
		var a_src = "1_a.txt";
		var q_cnt = 0; //問題の個数
		var q_num = 0;
		var name = '<?php echo $username; ?>';
		
		console.log(name);
		const q_select = document.getElementById('q_num');
		const ranknum_div = document.getElementById('ranking_num');
		const rank_div = document.getElementById('ranking');
		//選択された問題のランキングを表示
		q_select.addEventListener('change', (e) => {
			q_num = Number(e.target.value);
			ranknum_div.innerHTML = "問題No.";
			ranknum_div.innerHTML += e.target.value;
			ranknum_div.innerHTML += "のランキング(Top10)<br>";
			ranking();	
		});
		
		
		const CANVAS = document.getElementById("canvas");
     		const CONTEXT = CANVAS.getContext("2d");

        	let x = 0;
        	let y = 0;

        	let number = [
  	        0, 0, 0, 0, 0, 0, 0, 0, 0,
		0, 0, 0, 0, 0, 0, 0, 0, 0,
           	0, 0, 0, 0, 0, 0, 0, 0, 0,
	        0, 0, 0, 0, 0, 0, 0, 0, 0,
        	0, 0, 0, 0, 0, 0, 0, 0, 0,
	        0, 0, 0, 0, 0, 0, 0, 0, 0,
        	0, 0, 0, 0, 0, 0, 0, 0, 0,
           	0, 0, 0, 0, 0, 0, 0, 0, 0,
           	0, 0, 0, 0, 0, 0, 0, 0, 0
        	];

	        let field = [
        	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0,
          	0, 0, 0, 0, 0, 0, 0, 0, 0
        	];

        	let answer = [
          	6, 9, 5, 8, 2, 3, 4, 1, 7,
          	7, 1, 3, 4, 6, 5, 9, 8, 2,
          	4, 8, 2, 1, 9, 7, 6, 3, 5,
          	9, 2, 4, 6, 3, 1, 7, 5, 8,
          	1, 5, 6, 9, 7, 8, 2, 4, 3,
          	8, 3, 7, 5, 4, 2, 1, 9, 6,
          	5, 6, 9, 2, 8, 4, 3, 7, 1,
          	2, 7, 1, 3, 5, 9, 8, 6, 4,
          	3, 4, 8, 7, 1, 6, 5, 2, 9
        	];

		//問題の個数を読み込む
		$(function (){
			$.get(q_src, function (data) {
				let lines = data.split(/\n/);
				i = 0;
				while(lines[i] !== ""){	
					q_cnt++;
					i++;
				}
				for(i = 0; i < q_cnt; i++){
					const option = document.createElement('option');
					option.value = String(i + 1);
					option.textContent = "No." + String(i + 1);
					q_select.appendChild(option);
				}
			});
		});
		
		for(i = 0; i < 8; i++){	// 多重再生のため4個のオブジェクトを作成、移動音
			a1[i] = new Audio("idou.mp3");	
		}

		for(i = 0; i < 4; i++){	// 多重再生のため4個のオブジェクトを作成、移動音
			a2[i] = new Audio("kettei.mp3");	
		}
		//問題選択処理
		function setQuestion(){
			
			for(i = 0; i < 81; i++){
				field[i] = 0;
			}

/*			if(q_select.value == "問題No."){
				alert("問題を選択してください");
			}
 			else{
 */
			if(q_select.value != "問題No."){
			q_num = Number(q_select.value);
				//問題を取得
				$(function (){
					$.get(q_src, function (data) {
						let lines = data.split(/\n/);
						let question = lines.map(line => line.split(''));
						for(i = 0; i < 81; i ++){
							number[i] = Number(question[q_num-1][i]);
						}
						CONTEXT.clearRect(0, 0, 450, 450);
						drawField();
					});
				});
				//解答を取得
				$(function (){
                                        $.get(a_src, function (data) {
                                                let lines = data.split(/\n/);
                                                let ans_str = lines.map(line => line.split(''));
                                                for(i = 0; i < 81; i ++){
                                                        answer[i] = Number(ans_str[q_num-1][i]);
                                                }
                                        });
                                });
			}
		}

		//繰り返し処理の中身
		function showPassage(){
    			PassSec++;  //カウントアップ
    			var msg = "ボタンを押してから" + PassSec + "秒が経過しました。";    //表示文作成
    			document.getElementById("PassageArea").innerHTML = msg; //表示更新
		}

		function game_start(){
			if(q_select.value == "問題No."){
				alert("問題を選択してください");
				return;
			}else{
 			setQuestion();
 			tid = 1;
 			st = new Date().getTime();
 			PassSec = 0;    //カウンタのリセット
 			PassageID = setInterval('showPassage()', 1000); //タイマーをセット(1000ms間隔)
 			document.getElementById("startcount").disabled = true;  //開始ボタンの無効化
        		document.getElementById("answer").disabled = false; //　開始ボタンの有効化
            		document.getElementById("msg").innerHTML = '<span id="msg">空欄に数字を入れて完成させてみよう</span>';
			}
		}

		function reset(){   //リセット時の操作
    			if(tid == 0){   //ゲーム中でなければなにもしない
        			return;
    			}else if(tid == 1){
        			tid = 0;
        			st = new Date().getTime();
        			PassSec = 0;    //カウンタのリセット
        			//init();
        			clearInterval(PassageID);   //タイマーのクリア
        			document.getElementById("startcount").disabled = false; //　開始ボタンの有効化
 				document.getElementById("answer").disabled = true;  //開始ボタンの無効化
            		}
		}

		//ギブアップ処理
		function giveup(){
			if(tid == 0){
				return;
			}
			const give_result = confirm("ギブアップして解答を表示しますか?");
			if(give_result){
				for(let i = 0; i < 81; i++){
					if(number[i] == 0 || number[i] != answer[i]){
						number[i] = answer[i];
					}	
				}
				CONTEXT.clearRect(0, 0, 450, 450);
				drawField();

				reset();
			}
		}


			document.addEventListener("keydown", movePointer);

        	function drawField(){	// マス目作成
        		CONTEXT.strokeStyle = "green";
            		CONTEXT.lineWidth = 5;

            		for(let i= 150; i <= 300;i = i + 150){
                		CONTEXT.moveTo(i, 0);
                		CONTEXT.lineTo(i, 450);
                		CONTEXT.stroke();

                		CONTEXT.moveTo(0, i);
                		CONTEXT.lineTo(450, i);
                		CONTEXT.stroke();
			}

			CONTEXT.lineWidth = 1;

			for(let i= 50; i <= 450;i = i + 50){
				CONTEXT.moveTo(i, 0);
            			CONTEXT.lineTo(i, 450);
            			CONTEXT.stroke();

            			CONTEXT.moveTo(0, i);
            			CONTEXT.lineTo(450, i);
           			CONTEXT.stroke();
        		}

        		CONTEXT.font = "30px serif";
        		CONTEXT.textAlign = "center";

       	 		for(let i = 0; i < 81; i++){
            				if(number[i] == 0){
                				CONTEXT.fillStyle = "red";	// 空白の所の文字の色
                			}else{
                				CONTEXT.fillStyle = "blue";	// 最初から入力されている数字の色
             	 			}
                			if(number[i] + field[i] != 0){
			    			CONTEXT.fillText(number[i] + field[i], 25 + 50 * (i % 9), 35 + 50 * (Math.floor(i / 9)));
                			}
            		}
			

            		CONTEXT.lineWidth = 4;
            		CONTEXT.strokeStyle = "orange";
            		CONTEXT.beginPath();
            		CONTEXT.rect(x * 50, y * 50, 50, 50);	// 四角形を引く。
            		CONTEXT.stroke();
        	}

        	drawField();
		
		/* キーボードでどのキーを押したか判別 */
        	function movePointer(e){
			if(tid == 0){	// ゲーム中でなければなにもしない。
				return;
			}
            		if(e.key == "ArrowRight"){	// 右矢印を押した時
                		if(x < 8){		// xが8未満の時だけ、xを1つ増やす
                    			x = x + 1;
					a1[a1_cnt++].play();	//　移動した時の効果音
					a1_cnt &= 7;	// a1_cntが0～3の範囲になるように4になったら0に戻す
                		}
            		}else if(e.key == "ArrowLeft"){		//　左矢印を押した時
                		if(0 < x){		// xが0より大きい時に、xを1減らす
                    			x = x -1;
					a1[a1_cnt++].play();	//　移動した時の効果音
					a1_cnt &= 7;	// a1_cntが0～3の範囲になるように4になったら0に戻す
                		}
            		}else if(e.key == "ArrowDown"){		//　下矢印を押した時
                		if(y < 8){		// yが8未満の時だけ、yを1つ増やす
                    			y = y + 1;
					a1[a1_cnt++].play();	//　移動した時の効果音
					a1_cnt &= 7;	// a1_cntが0～3の範囲になるように4になったら0に戻す
                		}
            		}else if(e.key == "ArrowUp"){		//　上矢印を押した時
                		if(y > 0){		// yが0より大きい時に、yを1減らす
                    			y = y - 1;		
					a1[a1_cnt++].play();	//　移動した時の効果音
					a1_cnt &= 7;	// a1_cntが0～3の範囲になるように4になったら0に戻す
                		}
            		}else if(48 <= e.keyCode && e.keyCode <= 57){	// 数字をのキーを押された時
                		putNumber(e.keyCode - 48);
				a2[a2_cnt++].play();
				a2_cnt &= 3;
			//	a2.play();	// 入力時の音楽を再生
			}else if(e.keyCode == 46){	// 数字を削除
				del(e.keyCode);
			}
            		CONTEXT.clearRect(0, 0, 450, 450);
            		drawField();

/*            		let checker = true;
            		for(let i = 0; i < 81; i++){
                    		if(number[i] + field[i] != answer[i]) {
                        		checker = false;
             			}
            		}		

            		if(checker == true){
                		alert("おめでとうございます。クリアです！");
				a3.play();	// 完成時の音楽を再生
 				time = (en - st) / 1000;
 				clearInterval(PassageID);   //タイマーのクリア
 				tid = 0;    //操作停止
 				document.getElementById("startcount").disabled = false; //　開始ボタンの有効化
				document.getElementById("msg").innerHTML = '<font color="red">完成です</fonti>';
			}
*/		
		}
		/* 答え合わせ用の関数 */
		function check(){		
            		let checker = true;
            		for(let i = 0; i < 81; i++){
                    			if(number[i] + field[i] != answer[i]) {
                        			checker = false;
                    			}
            		}		
            		if(checker == true){
           //     		alert("おめでとうございます。クリアです！");
				a3.play();	// 完成時の音楽を再生
				en = new Date().getTime();	//終了時の時刻
 				time = (en - st) / 1000;	// ゲームのプレイ時間
 				clearInterval(PassageID);   //タイマーのクリア
 				tid = 0;    //操作停止
 				document.getElementById("startcount").disabled = false; //　開始ボタンの有効化
				document.getElementById("msg").innerHTML = '<font color="red">完成です</fonti>';
				var result = confirm("おめでとうございます。あなたのタイムは" + time +"秒でした.ランキングに登録しますか?");
				//ランキング登録処理
				if(result == true){
					$(function(){
						$.ajax({
							url: 'rank.php',
							type: 'POST',
							data: {
								'name': name,
								'time': time,
								'q_number':q_num
							}
						}).done(function(){
							ranking();
						});
					});
				}
				

			}
			else if(checker == false){
				alert("答えが違います。もう少し頑張りましょう！");
			}
		}
        	function putNumber(n) {	// キーボードを押された時
            		if (number[(y * 9) + x] == 0) {
                		field[(y * 9) + x] = n;
            		}
        	}
        	function del(n) {	// 入力数字を削除
            		if (n == 46) {
                		field[(y * 9) + x] = 0;
            		}
		}

		

		function ranking() {
			var str_qnum = String(q_num);
			
			$(function(){
				$.ajax({
					url: 'rank_sort.php',
					type: 'POST',
					data: {'q_number': str_qnum}	
				}).done(function(data){
					var lines = data.split("\n");
					console.log(lines);
					var table = "<table border=1 cellspacing=0 cellpadding=2>";
					table += "<tr><td>順位</td><td>タイム</td><td>ユーザー名</td><td>日時</td><tr>";
					for(let i = 0; i < lines.length-1; i++){
						//10位以内を表示
						if(i<10){
							var line = lines[i].split(",");
							table += "<tr><td>"+ (i+1) + "</td><td>" + line[2] + "</td><td>" + line[1] + "</td><td>" + line[0] + "</td></tr>";
						}
						else{
							break;
						}
					}
						table += "<table>";
						rank_div.innerHTML = table;
						rank_div.innerHTML += "<br><form method='post' name='rank_det' action='rank_det.php'><input type='hidden' name='q_number' value='' id='rank_qnumber'></form><a href='javascript:rank_det.submit()'>10位以下も含めたランキングはこちら</a><br>"
						document.getElementById("rank_qnumber").value = str_qnum;  	
				});
			});
		}
		
	</script>
	<br>

	</body>
</html>

