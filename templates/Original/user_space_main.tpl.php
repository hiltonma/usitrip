<?php 	/*user space left column*/
		echo '<div class="leftside4"><div class="left_bg4">';
			//user space left title
			//echo '<div class="bg3_title2"><h3>'.MY_TOURS.'</h3> <div class="bg3_shumin">'.WELCOME_YOU.'<span class="cu">'.db_to_html($first_or_last_name).'</span></div><div class="bg3_shumin"><span class="cu">'.HAVE_POINTS.$my_score_sum.' <img src="image/help.gif" /></span></div></div>';
			//user space left's left
			echo '<div class="bg3_left">';
			require("user_space_left_column.php");
			echo '</div>';
			//user space left's right
			echo '<div class="bg3_right2">';
				//content
				require(DIR_FS_CONTENT . $content . '.tpl.php');
			echo '</div>';
		echo '</div></div>';
		/*user space left column end*/
		
		/*user space right column*/
		echo '<div class="right_bg3">';
		require("my_space_right_column.php");
		echo '</div>';
		/*user space right column end*/
?>