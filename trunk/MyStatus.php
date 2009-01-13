<?php
/*
Plugin Name: MyStatus
Plugin URI: http://www.danielsands.co.cc
Description: A simple and easy to use plug-in that allows users to show their current status on their blog.
Version: 1.01
Author: Daniel Sands
Author URI: http://www.danielsands.co.cc
*/

/*  Copyright 2009  Daniel Sands  (email : daniel@durell.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$Version = "1.01";

	function initial_setup() { 
		global $wpdb;
		$table_name = $wpdb->prefix . "MyStatus";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		//for the inital setup we need to check that the table doesn't already exist, if it does we exit.
			$sql = "CREATE TABLE " . $table_name . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  time bigint(11) DEFAULT '0' NOT NULL,
					  text text NOT NULL,
					  UNIQUE KEY id (id)
					);";
				
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			//Create an inital entry to the database to confirm successful installation.
			$initalStatus = "Has installed MyStatus!";
			$insert = "INSERT INTO " . $table_name .
						" (time, text) " .
						"VALUES ('" . time() . "','" . $wpdb->escape($initalStatus) . "')";
			$results = $wpdb->query( $insert );
			
			
			//now create the initial settings for version, person_name, statusStructure and statusStyle

                        add_option('MyStatus_Version',$Version);

			add_option('MyStatus_Structure','<div id="currentStatus">' .
'<table>' .
'<tr>' .
'<td style="vertical-align:top;"><span class="name">%NAME%</span> </td>' .
'<td>' .
'<span class="status">%STATUS%  <a class="a" onclick="showPrevious();">[*]</a></span><br />' .
'<span id="previousStatus">%PREVSTATUS%</span>' .
'</td>' .
'</tr>' .
'</table>' .
'</div>');

			add_option('MyStatus_Style','<style type="text/css">' . 
				'#currentStatus { ' . 
				'position:absolute!important; ' . 
				'padding:3px 3px 3px 3px; top:0px; ' . 
				'left:200px; ' . 
				'display:block; ' . 
				'width:auto; ' . 
				'height:20px; ' . 
				'background-color:#00000!important; ' . 
				'font:bold 11px verdana; ' . 
				'text-transform:uppercase; ' . 
				'} ' . 
				'#currentStatus .status { ' . 
				'background-color:#C00000!important; ' . 
				'}' . 
				'</style>');

			add_option('MyStatus_Name','Daniel');

                        add_option('MyStatus_PrevEntries','6');

			return true;
		} else { 
		return false;
	    }
}

//hooks
add_action('get_sidebar', 'getstatus');
add_action('admin_menu', 'MyStatus_admin');
register_activation_hook(__FILE__,'initial_setup');


	
function MyStatus_admin() {
  add_options_page('My Plugin Options', 'MyStatus', 8, __FILE__, 'MyStatus_Options');
}

function MyStatus_Options() {


    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
		if( $_POST["addNew"] == 'Y' ) { 
		//add the new thing here!
		global $wpdb;
		$table_name = $wpdb->prefix . "MyStatus";
$insert = "INSERT INTO " . $table_name .
" (time, text) " .
"VALUES ('" . time() . "','" . $_POST["NewStatus_Text"] . "')";
$results = $wpdb->query( $insert );

		//show an updated message
		?><div class="updated"><p><strong><?php _e('Status changed.', 'mt_trans_domain' ); ?></strong></p></div><?php 
		} 
		
		if( $_POST["updateSettings"] == 'Y' ) { 
		//change the settings!
                update_option("MyStatus_Name", $_POST["MyStatus_Name"]);
                update_option("MyStatus_Structure", $_POST["MyStatus_Structure"]);
                update_option("MyStatus_Style", $_POST["MyStatus_Style"]);
                update_option("MyStatus_PrevEntries", $_POST["MyStatus_PrevEntries"]);

		//show an updated message
		?><div class="updated"><p><strong><?php _e('Settings saved.', 'mt_trans_domain' ); ?></strong></p></div><?php 
		} 
		
		//now show the options screen
		//MyStatus_Structure, MyStatus_Style, MyStatus_Name, MyStatus_PrevEntries
		?>
		<div class="wrap">
			<h2>MyStatus by <a href="http://www.danielsands.co.cc">Daniel Sands</a></h2>
			<p>Welcome to MyStatus, if you wish to change your current status simply fill in the text field below and hit "update".</p>
				<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<input type="hidden" name="addNew" value="Y">
					<table><tr>
						<td>
							<p>
								<?php echo (strtoupper(get_option('MyStatus_Name'))); ?> 
								<input type="text" name="NewStatus_Text" value="IS" size="40">
							</p>
						</td>
						<td>
							<p class="submit">
								<input type="submit" name="Submit" value="<?php _e('Update', 'mt_trans_domain' ); ?>" />
							</p>
						</td>
					</table></tr>
				</form>
				<h3>Settings</h3>
				<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<input type="hidden" name="updateSettings" value="Y">
					<table>
					<tr><td><p>
					Your name: </p></td><td><p><input type="text" name="MyStatus_Name" size="40" value="<?php echo(get_option("MyStatus_Name")); ?>">
					</p></td></tr>

					<tr><td><p>
					Number of previous status <br>text's to show: </p></td><td><p><input type="text" name="MyStatus_PrevEntries" size="40" value="<?php echo(get_option("MyStatus_PrevEntries")); ?>">
					</p></td></tr>

					<tr><td><p>
					Structure: </p></td><td><p><textarea name="MyStatus_Structure" rows="10" cols="40"><?php echo(str_replace( '\\', '' , get_option("MyStatus_Structure"))); ?></textarea>
					</p></td></tr>
					
					<tr><td><p>
					Style: </p></td><td><p><textarea name="MyStatus_Style" rows="10" cols="40"><?php echo(str_replace( '\\', '' , get_option("MyStatus_Style"))); ?></textarea>
					</p></td></tr>
					


					<tr><td><p class="submit">
					<input type="submit" name="Submit" value="<?php _e('Save Settings', 'mt_trans_domain' ); ?>" />
					</p></td></tr>
					</table>
				</form>
		</div>
<?php
} //end tag for function



function getstatus() { 
	$MyStatus_Structure = get_option("MyStatus_Structure");
        $MyStatus_Structure = str_replace( '\\', '' , $MyStatus_Structure);
	$MyStatus_Style = get_option("MyStatus_Style");

	//replace %NAME%, %STATUS, %PREVSTATUS%
	$MyStatus_Structure = str_replace( '%NAME%', get_option("MyStatus_Name") , $MyStatus_Structure);

	$curStatus = "";
	$prevStatus = "";
	
	global $wpdb;
	$table_name = $wpdb->prefix . "MyStatus";
	
	
	$sql = "SELECT * FROM " . $table_name . " ORDER BY time DESC LIMIT 1";
	$results = $wpdb->get_results( $sql );
if ($results) {

foreach ($results as $result) { 
$curStatus = $result->text;
}
} else { 
$curStatus = "'s MyStatus has broken, oh noes :(";
}

	$MyStatus_Structure = str_replace( '%STATUS%', $curStatus, $MyStatus_Structure);
	
	$sql = "SELECT * FROM " . $table_name . " ORDER BY time DESC LIMIT " . get_option("MyStatus_PrevEntries");
	$results = $wpdb->get_results( $sql );
	
	
	// list all prev status entries
	$cur_sep = '';
	$sep = "<br />";
	
	
	foreach( $results as $row ) {
	$result = $row->text;
		if ($cur_sep=='') { 
		  $cur_sep='1';
		} elseif ($cur_sep=='1') { 
			$prevStatus .= $result;
			$cur_sep = $sep;
		} else { 
			$prevStatus .= $cur_sep . $result;
		}
	}

$MyStatus_Structure = str_replace( '%PREVSTATUS%', $prevStatus , $MyStatus_Structure);

$MyStatus_Style = str_replace( '\\', '' , $MyStatus_Style );
echo ($MyStatus_Style);
echo ($MyStatus_Structure);
echo ('<script type="text/javascript">' .
'function showPrevious() { ' .
'var prevDIV = document.getElementById("previousStatus");' .
'if (prevDIV.style.display=="none") { ' .
'prevDIV.style.display = "block";' .
'} else { ' .
'prevDIV.style.display = "none";' .
'}' .
'}' .
'</script>');
}

?>