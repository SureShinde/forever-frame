<?php
require('./wp-blog-header.php');

$uid = $_GET['uid'];

if($uid != '') {

	$sel = "select id from wp_follow_post where id = '".$uid."'";
	$exec = mysql_query($sel);
	if(mysql_num_rows($exec) > 0){
		$udp = "UPDATE wp_follow_post SET  follow_status = 0 where id = '".$uid."'";
		$ud = mysql_query($udp);
		echo '<div align="center" style="margin-top:15px;"><b>You Are Successfully Unsubscribed</b></div>';

	
	} else {
		echo '<div align="center" style="margin-top:15px;"><b>IMPROPER URL</b></div>';

	}
} else {
	echo '<div align="center" style="margin-top:15px;"><b>IMPROPER URL</b></div>';
}
?>