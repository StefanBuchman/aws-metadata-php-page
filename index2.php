<?php
// Enable better php debugging
ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL);

// Credit
$author_name = 'Stefan Buchman';
$author_version = 'v1.0';
$author_project = 'AWS Metadata PHP Page';

// Dont use dashes - in declaring variables, break them, no idea.
// Be sure to end all http URLs with /, or some wont render data.
// We build array and the order below is how it builds the table
$curl_cmd = 'curl --connect-timeout 1';
$meta_host = '169.254.169.254';
$meta_data['ami-id'] = $ami_id = exec($curl_cmd." http://".$meta_host."/latest/meta-data/ami-id/");
$meta_data['instance-id'] = $instance_id = exec($curl_cmd." http://".$meta_host."/latest/meta-data/instance-id/");
$meta_data['availability-zone'] = $reg_az = exec($curl_cmd." http://".$meta_host."/latest/meta-data/placement/availability-zone/");
$meta_data['public-hostname'] = $public_hostname = exec($curl_cmd." http://".$meta_host."/latest/meta-data/public-hostname/");
$meta_data['public-ipv4'] = $public_ipv4 = exec($curl_cmd." http://".$meta_host."/latest/meta-data/public-ipv4/");
$meta_data['local-hostname'] = $local_hostname = exec($curl_cmd." http://".$meta_host."/latest/meta-data/local-hostname/");
$meta_data['local-ipv4'] = $local_ipv4 = exec($curl_cmd." http://".$meta_host."/latest/meta-data/local-ipv4/");
$git_url = 'https://github.com/StefanBuchman/aws-metadata-php-page';
$S3_url = "https://s3-us-west-1.amazonaws.com/networkpulse/com/public/images";
$S3_image = "$S3_url.aws.png";
$server_name = $_SERVER['SERVER_NAME'];
$server_ip = $meta_data['public-ipv4'];
$server_software = $_SERVER['SERVER_SOFTWARE'];
$client_ip = $_SERVER['REMOTE_ADDR'];
$client_agent = $_SERVER['HTTP_USER_AGENT'];
$page_title =  'AWS Cloud - ' . $server_name;
$php_self = $_SERVER['SCRIPT_NAME'];

/** Check for page refresh, defaults to 5 mins **/
if (empty($_GET['refresh'])) {
	 $page_refresh = 300;
   } else {
	 $page_refresh = $_GET['refresh'];
}

/** find the availability zone **/
 function findAZ ($az) {
	// check if the value is null/empty
	if (empty($az) || !isset($az)) {
	return 'Error: unknown az';
	}
	$az = strtolower($az);
	return $az;
 } //end function

 /** find the region **/
 function findRegion ($region) {
 	// check if the value is null/empty
	if (empty($region) || !isset($region)) {
	return 'Error: unknown region';
	}
	$region = substr($region, 0,-1);
	$region = strtoupper($region);
	return $region;
 } //end function

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<title><?php echo $author_project.' '.$author_version; ?></title>
	<meta http-equiv="refresh" content="<?php echo $page_refresh; ?>" />
	<meta http-equiv="Content-Language" content="en-us" />

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="MSSmartTagsPreventParsing" content="true" />

	<meta name="description" content="Description" />
	<meta name="keywords" content="Keywords" />

	<meta name="author" content="<?php echo $author_name; ?>" />

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<style type="text/css" media="all">@import "css/styles.css";</style>
</head>
<body>
	<div class="jumbotron jumbotron-fluid">
 		<div class="container">
 			<h1 class="display-3">Amazon Web Services</h1>
			<hr class="my-4">

 			<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
				<div class="btn-group mr-2" role="group" aria-label="Label group">
					<button type="button" class="btn btn-secondary" onclick="location.href='index2.php?refresh=0';">Refresh</button>
				</div>
				<div class="btn-group btn-group-sm" role="group" aria-label="Refresh Period">
					<button type="button" class="btn btn-secondary" onclick="location.href='index2.php?refresh=2';">2s</button>
					<button type="button" class="btn btn-secondary" onclick="location.href='index2.php?refresh=5';">5s</button>
					<button type="button" class="btn btn-secondary" onclick="location.href='index2.php?refresh=30';">30s</button>
					<button type="button" class="btn btn-secondary" onclick="location.href='index2.php?refresh=60';">1m</button>
					<button type="button" class="btn btn-secondary" onclick="location.href='index2.php?refresh=300';">5m</button>
				</div>
			</div>
 		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				<h2>EC2 Metadata</h2>
				<?php
			    //metadata table
			    echo '<table border="0" bgcolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">';
			    echo '<tr><th align="left">Metadata</th><th align="left">Value</th></tr>';
			    foreach($meta_data as $x=>$x_value) {
			       echo '<tr>';
			    	echo '<td nowrap><span class="key">'. $x . '</span></td>';
			            echo '<td no wrap><span class="value">'. $x_value . '</span></td>';
			       echo '</tr>';
			    }
			    echo '</table>';
		    	?>
			</div>
			<div class="col-md-4 bg-light">
				<h2>AWS  - Region</h2>
				<p><?php echo findRegion($meta_data['availability-zone']); ?></p><br>
				<h3>Availability Zone</h3>
				<p><?php echo findAZ($meta_data['availability-zone']); ?></p><br>
				<h3>Information</h3>
				<p>Server: <?php echo $server_software.'<br>Public IP: ';?><a href="http://<?php echo $server_ip; ?>"><?php echo $server_ip; ?></a></p>
				<p>Client: <?php echo $client_agent.'<br>IP: '.$client_ip; ?></p>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
</body>
</html>
