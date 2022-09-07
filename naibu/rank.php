<?php 
if( isset($_POST['name']) && isset($_POST['time']) ){ 
 $fw = fopen("rank.txt", "a"); // 追記する 
 fwrite( $fw, date("y/m/d H:i:s").",".$_POST['name'].",".$_POST['time'].",".$_POST['q_number']."\n"); 
 fclose($fw); 
} 
?>
