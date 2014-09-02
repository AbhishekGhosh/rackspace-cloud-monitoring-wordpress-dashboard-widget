<?php
/*
Plugin Name: Rackspace Cloud Intellence
Plugin URI: https://thecustomizewindows.com/
Description: The Wordpress plugin adds a widget into your Admin Dashboard. After activating the plugin, go to your Admin Dashboard Home and see the new widget.
Version: 1.0
Author: Abhishek Ghosh
Author URI: http://dr.abhishekghosh.net/
License: GPL3
*/
/*
GNU GPL MEANS COPYLEFT. MORON WP DEVELOPERS ''WRITE COPYRIGHT BY'' HERE. 
DO NOT FORK BUT COPY RAW IN YOUR OWN.
HUGELY COMMENTED.
*/
// define the function name as ci_rackspace_intelligence

function ci_rackspace_intelligence () {
	wp_add_dashboard_widget('wp_rackspace_intelligence_widget', '<i class="dashicons dashicons-networking sswheading"></i>Server Details', 'ci_rackspace_intelligence_output');
}
add_action('wp_dashboard_setup', 'ci_rackspace_intelligence' );
// that dashicon should be changed to rackspace one
// thats default borrowed from wordpress css

// now construct function, see line 18
function ci_rackspace_intelligence_output() {
// these are fake stuffs to test
	$loadresult = exec('uptime');
	$whoami		= exec('whoami');
	$path		= ABSPATH;
	$hostname	= trim(exec('hostname'));
	$php		= phpversion();
	$mysql		= mysql_get_server_info();

// pure php stuffs	

	if ( strlen($hostname) > 30 ) {
		$host = '<tr><td><b>Host Name</b></td><td>&nbsp;&nbsp;</td><td>: <span class="slidename" title="'.$hostname.'">' . substr($hostname, 0 , 30) . '..<span></td></tr>';
	} else {
		$host = '<tr><td><b>Host Name</b></td><td>&nbsp;&nbsp;</td><td>: ' . $hostname . '</td></tr>';
	}
	
	if ( strlen($path) > 30 ) {
		$abspath = '<tr><td><b>Server Path</b></td><td>&nbsp;&nbsp;</td><td>: <span class="slidename"  title="'.$path.'">' . substr($path, 0 , 30) . '..<span></td></tr>';
	} else {
		$abspath = '<tr><td><b>Server Path</b></td><td>&nbsp;&nbsp;</td><td>: ' . $path . '</td></tr>';
	}	
// 	we can fetch in this way normally
	$ip			= gethostbyname($hostname);
	
	preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs);

	$uptime = explode(' up ', $loadresult);
	$uptime = explode(',', $uptime[1]);
	$uptime = $uptime[0].', '.$uptime[1];
		
		$data = '';
		$data .= '<table>';
		$data .= $host;
		$data .= '<tr><td><b>Server IP</b></td><td>&nbsp;&nbsp;</td><td>: ' . $ip . '</td></tr>';
		$data .= $abspath;
		$data .= '<tr><td><b>Load Averages</b></td><td>&nbsp;&nbsp;</td><td>: ' . $avgs[1]. ', ' . $avgs[2] . ', ' . $avgs[3] . '</td></tr>';
		$data .= '<tr><td><b>Server is UP Since</b></td><td>&nbsp;&nbsp;</td><td>: '. $uptime . '</td></tr>';
		$data .= '<tr><td><b>PHP Version</b></td><td>&nbsp;&nbsp;</td><td>: '. $php . '</td></tr>';
		$data .= '<tr><td><b>MySQL Version</b></td><td>&nbsp;&nbsp;</td><td>: '. $mysql . '</td></tr>';
		$data .= '<tr><td><b>PHP is running under</b></td><td>&nbsp;&nbsp;</td><td>: '. $whoami . '</td></tr>';
		$data .= '</table>';
		
	echo $data;
}

// above we will call cloud intelligence via api and show graph

function ci_enqueue_uitooltip($hook) {
    if( 'index.php' != $hook )
        return;
    wp_enqueue_script( 'jquery-ui-tooltip' );
	wp_enqueue_style( 'uicss', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');
}
add_action( 'admin_enqueue_scripts', 'ci_enqueue_uitooltip' );

// sane way to give the webmasters a way to unhook for minification, see line 18

function ci_add_styles_and_scripts() {
	?>
	<style>
	#wp_rackspace_intelligence_widget .hndle {
		background: none repeat scroll 0 0 #222222;
		color: #fff;
	}
	#wp_rackspace_intelligence_widget .inside {
		background: none repeat scroll 0 0 #eee;
		margin: 0;
		padding-top: 10px;
		text-shadow: 1px 1px 0 #fff;
	}
	#wp_rackspace_intelligence_widget .hndle > span > i {
		color: #ff8800;
		display: inline-block;
		font-size: 17px !important;
		font-weight: normal !important;
		margin: 0 5px 0 0;
		vertical-align: middle;
	}
	.slidename {
		border-bottom: 1px dashed #ddd;
		cursor: pointer;
		padding: 0 0 3px;
	}
	 .ui-tooltip, .arrow:after {
		background: #000000;
	}
	.ui-tooltip {
		padding: 10px;
		color: white;
		border-radius: 5px;
		font-size: 13px;
	}
	.arrow {
		width: 70px;
		height: 16px;
		overflow: hidden;
		position: absolute;
		left: 50%;
		margin-left: -35px;
		bottom: -16px;
	}
	.arrow.top {
		top: -16px;
		bottom: auto;
	}
	.arrow.left {
		left: 20%;
	}
	.arrow:after {
		content: "";
		position: absolute;
		left: 20px;
		top: -20px;
		width: 25px;
		height: 25px;
		box-shadow: 6px 5px 9px -9px black;
		-webkit-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
		transform: rotate(45deg);
	}
	.arrow.top:after {
		bottom: -20px;
		top: auto;
	}
	</style>
	<script>
		jQuery( document ).ready(function() {
			jQuery(".slidename").tooltip({
				position: {
					my: "center bottom-20",
					at: "center bottom",
					using: function( position, feedback ) {
						jQuery( this ).css( position );
						jQuery( "<div>" )
						.addClass( "arrow" )
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
					}
				},
			})
			.dynamic({ bottom: { direction: 'down', bounce: true } });
		});
	</script>
	<?php
}
// debug this plugin first
add_action( 'admin_head-index.php', 'ci_add_styles_and_scripts' );
