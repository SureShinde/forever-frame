<?php
/*
Plugin Name: Follow My Blog Post Plugin
Plugin URI: http://wordpress.worldwebtechnology.com/FollowPost
Description: allows visitors to follow changes or comments on a particular posts. 
Version: 1
Author: World Web Technology
Author URI: http://wwww.worldwebtechnology.com/
*/


global $wpdb;
define('PLUGIN_FOLLOW_001', 'follow-my-blog-post');
define('PLUGIN_Follow_Table', 'wp_follow_blog_post');
define('PLUGIN_Follow_Log_Table', 'wp_follow_blog_post_log');
define('wwfplevel','8');

function ww_fp_sep() {
	if (get_option('permalink_structure') == '') {
		$sep = '&amp;';
	} else {
		$sep = '?';
	}
	return $sep;
} 

function getPostData($id) {

	$selW = "select post_title,post_name,post_content from wp_posts where  ID = '".$id."'";
	$resW = mysql_query($selW);
	$dataW = mysql_fetch_assoc($resW);
	return $dataW;
}

// calls the setup function on activation
register_activation_hook( __FILE__, 'ww_fp_install' );
register_deactivation_hook( __FILE__, 'ww_fp_uninstall' );



// does the initial setup
function ww_fp_install() {
	$cur = dirname(__FILE__);
	rename($cur."/unsubscribe.php", ABSPATH."/unsubscribe.php");

	global $wpdb;
	
	$Q = "CREATE TABLE IF NOT EXISTS  ".PLUGIN_Follow_Table." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `comment_post_ID` bigint(20) NOT NULL DEFAULT '0',
			  `comment_author_email` varchar(100) NOT NULL,
			  `user_id` bigint(20) NOT NULL DEFAULT '0',
			  `follow_status` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			)";
	$Res = mysql_query($Q);		
	
	$Q1 = "CREATE TABLE IF NOT EXISTS ".PLUGIN_Follow_Log_Table." (
		  `log_id` int(11) NOT NULL AUTO_INCREMENT,
		  `comment_post_ID` bigint(20) NOT NULL,
		  `user_email` varchar(100) NOT NULL,
		  `mail_data` longtext NOT NULL,
		  `log_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		   PRIMARY KEY (`log_id`)
			)";
	$Res1 = mysql_query($Q1);
	
	$opt1 = WP_PLUGIN_URL."/".PLUGIN_FOLLOW_001."/images/follow.gif";
	$opt2 = WP_PLUGIN_URL."/".PLUGIN_FOLLOW_001."/images/remove.gif";
	update_option('f_img_path', $opt1);
	update_option('r_img_path', $opt2);
	
}

// does the initial setup
function ww_fp_uninstall() {
	$cur = dirname(__FILE__);
	rename(ABSPATH."/unsubscribe.php",$cur."/unsubscribe.php" );
	global   $wpdb;

}	



add_action('init', 'ww_fp_do_filter'); // 1
add_action('comment_form', 'ww_fp_comment_form'); // 2 
add_action('comment_post', 'ww_fp_comment_post',1,2); // 3 
add_action('wp_set_comment_status', 'follow_post_call',1,2); // 4 
add_action('save_post', 'ww_fp_save_post'); // 5 


function ww_fp_do_filter() {
	//if( is_single() ) {
		add_filter('the_content', 'ww_fp_follow_filter', 1);
	//}
}

function ww_fp_follow_filter($post) {
	?>
	<style type="text/css">
		#followform {
			padding-top:5px;
			text-align:left;
			
		}
	</style>	
	<?php
	$user = wp_get_current_user();
	$userid = $user->ID;
	$comment_author_email = $user->user_email;
	
	if(isset($_POST['followstatus']) ) {
	//print_r($_POST);
	$comment_post_ID = get_the_ID();
	
	
		$follow_status = $_POST['followstatus'];
		if($follow_status != '1') { $follow_status = 1;}
		else { $follow_status = 0;}
		
		
		$sel  = "select id from ".PLUGIN_Follow_Table."  where user_id = '".$userid."' and comment_post_ID = '".$comment_post_ID."' ";
		$exe  = mysql_query($sel);
		$num  = mysql_num_rows($exe);
		if($num <= 0) {
			
			$ins = "Insert Into ".PLUGIN_Follow_Table." SET 
					comment_post_ID = '".$comment_post_ID."',
					comment_author_email = '".$comment_author_email."',
					user_id = '".$userid."',
					follow_status = '".$follow_status."'";
					
			$res = mysql_query($ins);		
			
		} else {
		
			$data = mysql_fetch_array($exe);
			$id = $data['id'];
			
			$udp = "UPDATE ".PLUGIN_Follow_Table." SET 
					comment_author_email = '".$comment_author_email."',
					user_id = '".$userid."',
					follow_status = '".$follow_status."'
					where id = '".$id."'";
			
			$resudp = mysql_query($udp);		
		}
	}
	

	
	if($userid != '0') {
	
		$sel  = "select follow_status from ".PLUGIN_Follow_Table."  where user_id = '".$userid."'";
		$exe  = mysql_query($sel);
		$num  = mysql_num_rows($exe);
		$data = mysql_fetch_array($exe);
		
		$follow_status = $data['follow_status'];
		if($follow_status != '1' ) { $follow_status = '0';}
		
		
		$post1  = "<form name='followform' id='followform' method='post' action='".$_SERVER['REQUEST_URI']."'>";
		if($follow_status != '1') {
			
			$post1 .= "<input type='image' src='".get_option('f_img_path')."' name='follow' alt='Follow Me' title='Follow Me''>";	
		
		} else {
			$post1 .= "<input type='image' src='".get_option('r_img_path')."' name='remove' value='Remove Me' title='Remove Me'>";	
		}
		
		$comment_post_ID = get_the_ID();
		$seln  = "select count(*) as cnt from ".PLUGIN_Follow_Table." where comment_post_ID = '".$comment_post_ID."'
				  and follow_status = 1";
		$exen  = mysql_query($seln);
		$datan = mysql_fetch_assoc($exen);
		$numn = $datan['cnt'];
		
		 		  
		$post1 .= " (".$numn." Users)";
		
		$post1 .= "<input type='hidden' name='followstatus' value='".$follow_status."'>";		
		$post1 .= "</form>";	
		$post = $post1.$post;
	}
	
	return $post;
}

function ww_fp_comment_form() {
?>
	<style type="text/css">
		#followstatus_chk {
			width:20px !important;
		}
	</style>

<?php
	$user = wp_get_current_user();
	$userid = $user->ID;
	$follow_status = '0';
	$f_text = "<b>Follow Me</b>"; 
	
	if($userid != '0' ){
		
		$sel  = "select follow_status from ".PLUGIN_Follow_Table."  where user_id = '".$userid."'";
		$exe  = mysql_query($sel);
		$num  = mysql_num_rows($exe);
		$data = mysql_fetch_array($exe);
		$follow_status = $data['follow_status'];
		if($follow_status != '0' && $follow_status != '' ) { 
			$follow_status = '1';
			$f_text = "<b>Uncheck Checkbox To remove From Following</b>"; 
		}
	}
?>

	<div id="zrx_captcha2">
		<table cellpadding="0" cellspacing="0" style="padding-bottom:5px;">
			<tr>
				<td align="left" valign="top">
					<input type="checkbox" name="followstatus_chk" id="followstatus_chk"  <?php if($follow_status == '1') { echo "checked=\"checked\"";} ?>  />
					<?php echo $f_text; ?>
				</td>
			</tr>
		</table>
	</div>
	<script type="text/javascript">
	//<![CDATA[
	
	for( i = 0; i < document.forms.length; i++ ) {
		if( typeof(document.forms[i].followstatus_chk) != 'undefined' ) {
			commentForm = document.forms[i].comment.parentNode;
			break;
		}
	}
	var commentArea = commentForm.parentNode;
	var captchafrm = document.getElementById("zrx_captcha2");
	commentArea.insertBefore(captchafrm, commentForm);
	//commentArea.publicKey.size = commentArea.author.size;
	//commentArea.publicKey.className = commentArea.author.className;
	//]]>
</script>
<?php 
}


function ww_fp_comment_post($comment_ID,$appStatus) {

		$user = wp_get_current_user();
		$userid = $user->ID;
		$comment_author_email = $user->user_email;
		$comment_post_ID = $_POST['comment_post_ID'];
		
		//print_r($_POST);
		
		
	if($userid == '0' && $_POST['followstatus_chk'] != 'on' ) {} else {
	
			if($_POST['followstatus_chk'] == 'on') {$follow_status = 1;} 
			else { $follow_status = 0;}
		
			if($userid == '0') {
				$comment_author_email = $_POST['email'];
			}
			
			if(trim($comment_author_email) != '') {
		
				$sel  = "select id from ".PLUGIN_Follow_Table."  where comment_author_email = '".$comment_author_email."' and comment_post_ID = '".$comment_post_ID."'";
				$exe  = mysql_query($sel);
				$num  = mysql_num_rows($exe);
							
				if($num <= 0) {
				
					$ins = "Insert Into ".PLUGIN_Follow_Table." SET 
							comment_post_ID = '".$comment_post_ID."',
							comment_author_email = '".mysql_escape_string(stripslashes(trim($comment_author_email)))."',
							user_id = '".$userid."',
							follow_status = '".$follow_status."'";
						
					$res = mysql_query($ins);		
				
				} else {
				
					$data = mysql_fetch_array($exe);
					$id = $data['id'];
					
					$udp = "UPDATE ".PLUGIN_Follow_Table." SET 
							comment_post_ID = '".$comment_post_ID."',
							comment_author_email = '".$comment_author_email."',
							follow_status = '".$follow_status."'
							where id = '".$id."'";
				
					$resudp = mysql_query($udp);
				}
			
				
			}
			
			if($appStatus == '1' || $appStatus === 'approve' ) {
				$appStatus = 'approve';
				follow_post_call($comment_ID,$appStatus);
			}
				
	}	
			
}


function follow_post_call($comment_ID,$appStatus,$extraParam = '') {

	
		
		if($extraParam != '') {
			$comment_post_ID = $extraParam;
			$appStatus = 'approve';
		} else {
			$selC = "select comment_post_ID from  wp_comments  where comment_ID = '".$comment_ID."'";
			$exeC = mysql_query($selC);
			$dataC = mysql_fetch_assoc($exeC);
			$comment_post_ID = $dataC['comment_post_ID'];
		}
		
							
		if( $appStatus === 'approve' ) {
		//$comment_post_ID = $_POST['comment_post_ID'];
		
		$sel = "select * from ".PLUGIN_Follow_Table."  where 	follow_status = 1 and comment_post_ID='".$comment_post_ID."'";
		$exe  = mysql_query($sel);
		$num  = mysql_num_rows($exe);
			
		while ( $data = mysql_fetch_assoc($exe) ) {
		
			$emails[] =   $data['comment_author_email'];
			$ids[]    =   $data['id'];
		}
		
		
		$Pdata = getPostData($comment_post_ID); 
		$ptitle = $Pdata['post_title'];
		
		$fromEmail = get_option('admin_email');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Admin '.$fromEmail.'' . "\r\n";
		
		$subject = "Post ".$ptitle." Updated at <a href='".get_option('siteurl')."'>".get_option('blogname')."</a>";
		
			for ($k_=0;$k_<count($ids);$k_++) {
			
				$unsubscribe_url = get_option('siteurl')."/unsubscribe.php?uid=".$ids[$k_];
	
				$message = "Post ".$ptitle." Updated  <br> 
							If You Want To See Page Click below link 
							<a href='".get_permalink($comment_post_ID)."'>".get_permalink($comment_post_ID)."</a> <br>							
							If You want to unsubscribe <a href=\"".$unsubscribe_url."\">Click Here</a>";
				
				$mail_data = $subject. "%$%$%". $message; 
				//$setmail = mail($emails[$k_], $subject, $message, $headers);
				if(1) {
					
					$ins = "INSERT into ".PLUGIN_Follow_Log_Table." SET
							comment_post_ID = '".$comment_post_ID."',
							user_email = '".$emails[$k_]."',
							mail_data = '".mysql_escape_string(stripslashes(trim($mail_data)))."',
							log_date = NOW()";
							
					$insQ = mysql_query($ins);		
				}
			}
		
		}
		
}


function ww_fp_save_post($postID) {

/*	$newpostID = wp_is_post_revision($postID);
	if($newpostID) {
		$finalID = $newpostID;
		$Pdata = getPostData($finalID);
		$post_name = $Pdata['post_name'];
		$nidArr = explode('-',$post_name);
		
		if(isset($nidArr[2]) && $nidArr != '' ){
			$nid = $nidArr[2];
			$postArr =  explode('-',$post_name);
			$postArr[2] =  $nid-1;
			
			//echo "<br>".print_r($postArr);
			$new_post_name = join('-',$postArr);
			//echo  $new_post_name;
			
		    $selQ = "select post_title,post_content from wp_posts where  post_name  = '".$new_post_name."'";
			$resQ = mysql_query($selQ);
			$data = mysql_fetch_assoc($resQ);
			if( mysql_num_rows($resQ) > 0) {
		
				$OldContent = $data['post_content'];
				
				echo "<br>Old".$OldContent;
				$NewContent = $Pdata['post_content'];
				echo "<br>New".$NewContent;
				
				if($NewContent != $OldContent)	 {
					echo "Process";
				} else {
					echo "NO Process";
				}
			}
		}
	} */
	
	$newpostID = wp_is_post_revision($postID);
	//print_r($_POST);
	if($newpostID) {
		$finalID = $newpostID;
		$Pdata = getPostData($postID);
		$OldContent = $Pdata['post_content']; 
		
		$Ndata = getPostData($finalID);
		$NewContent = $_POST['content']; 
		
		//echo "<br>Old Content".$OldContent;
		
		if($NewContent != $OldContent)	 {
			follow_post_call('','approve',$finalID);
		} else {
				echo "NO Process";
		}
	}	
	else {
		$finalID = $postID;
	}

	
}


/* Admin Menu */ 
add_action('admin_menu', 'ww_fp_add_admin');

function ww_fp_add_admin() {
	
 	add_options_page('Follow My Blog Post', 'Follow My Blog Post', wwfplevel, 'followpostsettings', 'ww_fp_options'); // add setting page
}


function ww_fp_options() {

	echo '<div class="wrap">';
	
	global $wpdb;
	
	if(isset($_GET['settings'])  && $_GET['settings'] == '1') {
	
		if(isset($_POST['fj-set-submit'])) {

			update_option('f_img_path', $_POST['f_img_path']);
		
			update_option('r_img_path', $_POST['r_img_path']);
			
			echo '<div id="message" class="updated fade"><p><strong>Changes Saved</strong></p></div>';

		
		}

		echo '<div class="wrap">';
			
		echo '<div style="float:left;"><h2>Follow My Blog Post Options</h2></div><div style="float:right;margin-top:24px;"><a href="?page=followpostsettings">Main Page</a></div>';
		
		echo '<div style="clear:both;">';
		echo '<form action="" method="post">';
		

		echo '<p>
				<label for="wppa-thumbsize">Follow Me: <small>Changing the URL of follow me image.</small></label><br />
				<input type="text" size="60" name="f_img_path" id="wppa-tumbsize" value="' . get_option('f_img_path') .'" />
			</p>';
		
		echo '<p>
				<label for="wppa-fullsize">Remove Me: <small> Changing the URL of remove me image.</small></label><br />
				<input type="text"  size="60" name="r_img_path" id="wppa-fullsize" value="' . get_option('r_img_path') .'" />
			</p>';
			
		
		echo '<p>
				<input type="submit" name="fj-set-submit" value="Save Changes" />
			</p>';
		echo '</form>';
		echo '</div>';
		
		echo '</div>';
	
	}
	
	else if(isset($_GET['postid']) && $_GET['postid'] != '' ) {
	
			$mydata = getPostData($_GET['postid']);
			$post_title = $mydata['post_title'];
			
		if(isset($_GET['log']) && $_GET['log'] != '') { 
			$log = $_GET['log'];
			echo "<h2>View  Logs</h2><br />";
			$sel2  = "SELECT comment_author_email  FROM " . PLUGIN_Follow_Table . " where id = '".$log."'";		
			$exe   =  mysql_query($sel2);
			$data1 =  mysql_fetch_assoc($exe);
			$email_ = $data1['comment_author_email'];
			
	

			$sel2 = "SELECT *  FROM " . PLUGIN_Follow_Log_Table . " where user_email = '".$email_."' and  comment_post_ID = '".$_GET['postid']."'";		
			$albums2 = $wpdb->get_results($sel2 , 'ARRAY_A');
			echo '<div style="clear:both">';
			echo '<div style="float:left;height:20px;width:100px;"><b>User Mail Id : </b></div><div style="float:left;" >'.$email_.'</div>';
			echo '<div style="clear:both;float:left;height:20px;width:100px;"><b>Post Title : </b></div><div style="float:left;" >'.$post_title.'</div>';
			echo '</div>';
			
			if (!empty($albums2)) {
				
				echo '<table class="widefat" >
						<thead>
						<tr>
							<th scope="col">Mail Subject</th>
							<th scope="col">Mail Body</th>
							<th scope="col">Date</th>
						</tr>
						</thead>';
					
					$alt = ' class="alternate" ';
					
					foreach ($albums2 as $data) {
						
						$mail_data = $data['mail_data'];
						$mailArr = explode('%$%$%',$mail_data);
						$datetime = new DateTime($data['log_date']);
						$newdate = $datetime->format('jS, F Y h:i:s A');

						
							
					?>
						 <tr  <?php echo $alt;?> >
							<td><?php echo $mailArr[0];?></td>
							<td><?php echo nl2br($mailArr[1]); ?></td>	
							<td><?php echo $newdate; ?></td>	
						</tr>
									
					<?php if ($alt == '') { $alt = ' class="alternate" '; } else { $alt = '';}
					}
					?>
					
					
					</table>
			<?php
			
			} else {
				echo "<div style='clear:both;margin-top:50px;'><b>NO Mail Sent</b></div>";
			}
			
		} else { 
			
		
				if(isset($_POST['Sub']) && $_POST['Sub'] == 'Subscribe') {
					//print_r($_POST);
					if( !empty($_POST['chkid']) ) {
						
						for($k_=0;$k_<count($_POST['chkid']);$k_++ ) {
								$udp = "Update ".PLUGIN_Follow_Table." SET  follow_status = 1 where id = '".$_POST['chkid'][$k_]."'";
								$res = mysql_query($udp);
						}
					}
				}
		
				if(isset($_POST['UnSub']) && $_POST['UnSub'] == 'UnSubscribe') {
					//print_r($_POST);
					if( !empty($_POST['chkid']) ) {
						
						for($k_=0;$k_<count($_POST['chkid']);$k_++ ) {
								$udp = "Update ".PLUGIN_Follow_Table." SET  follow_status = 0 where id = '".$_POST['chkid'][$k_]."'";
								$res = mysql_query($udp);
						}
					}
				}
		
		
				$postid = $_GET['postid'];
				echo "<h2>Subscribed  Users For- ".$post_title."</h2><br />";
				$sel = "SELECT *  FROM " . PLUGIN_Follow_Table . " where comment_post_ID = '".$postid."'";		
				$albums = $wpdb->get_results($sel , 'ARRAY_A');
		
				if (!empty($albums)) {
					
					echo '<form name="formuser" method="post" action="">';
					echo '<table class="widefat">
							<thead>
							<tr>
								<th scope="col"></th>
								<th scope="col">User Email</th>
								<th scope="col">User Type</th>
								<th scope="col">Subscribed</th>
								<th scope="col">View Log</th>				
							</tr>
							</thead>';
						
						$alt = ' class="alternate" ';
						
						foreach ($albums as $data) {
							
							$user_id = $data['user_id'];
							$follow_status = $data['follow_status'];
							
							if($user_id != '0') {
								$userType = 'Registered User';
							}	else {
								$userType = 'Guest';
							}	
							
							if($follow_status != '0') {
								$followmsg = 'Yes';
							}	else {
								$followmsg = 'No';
							}			
						?>
							 <tr  <?php echo $alt;?> >
								<td><input type="checkbox" name="chkid[]" value="<?php echo $data['id'] ?>" /></td>
								<td><?php echo $data['comment_author_email'];?></td>
								<td><?php echo $userType; ?></td>	
								<td><?php echo $followmsg; ?></td>	
								<td><a href="<?php echo $_SERVER['REQUEST_URI'].'&log='.$data['id']; ?>" class="edit">View Log</a></td>
							</tr>
										
						<?php if ($alt == '') { $alt = ' class="alternate" '; } else { $alt = '';}
						}
						?>
						
						<tr>
							<td colspan="5">
								<input type="submit" name="Sub" value="Subscribe"  />
								<input type="submit" name="UnSub" value="UnSubscribe"  />
		
							</td>
						</tr>	
						</table>
						</form>			
				<?php
				
				} else { 
					echo "No Posts yet."; 
				}

		}	
	
	} else {

		echo '<div style="float:left;"><h2>Post List - Select A Post To View Subscribed Users</h2></div><div style="float:right;margin-top:24px;"><a href="?page=followpostsettings&settings=1">Setting Page</a></div>';

		$albums = $wpdb->get_results("SELECT distinct(comment_post_ID) FROM " . PLUGIN_Follow_Table, 'ARRAY_A');
		
		if (!empty($albums)) {
		
			echo '<table class="widefat">
					<thead>
						<tr>
							<th scope="col">Post Name</th>
							<th scope="col">View Uses</th>				
						</tr>
					</thead>';
				
				$alt = ' class="alternate" ';
				
				foreach ($albums as $data) {
				
					$Pdata = getPostData($data['comment_post_ID']);
					$post_title = $Pdata['post_title'];
					?>
					 <tr <?php echo $alt;?> ><td><?php echo $post_title;?></td>
						<td><a href="<?php echo $_SERVER['REQUEST_URI'].'&postid='.$data['comment_post_ID']; ?>" class="edit">View Users</a></td>
					</tr>
								
					<?php if ($alt == '') { $alt = ' class="alternate" '; } else { $alt = '';}
				}
				
			echo '</table>';
		
		} else { 
			echo "No Posts yet."; 
		}
		
		
	}
	
	echo '</div>';	

		
}

?>