<?php
define("ZDAPIKEY", "YOURAPIKEY");
define("ZDUSER", "YOURUSERNAME");
define("ZDURL", "https://SUBDOMAIN.zendesk.com/api/v2");

function curlWrap($url, $json)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output);
	return $decoded;
}
foreach($_POST as $key => $value){
	if(preg_match('/^z_/i',$key)){
		$arr[strip_tags($key)] = strip_tags($value);
	}
}
$create = json_encode(array('ticket' => array('subject' => $arr['z_subject'], 'comment' => array( "value"=> $arr['z_description']), 'requester' => array('name' => $arr['z_name'], 'email' => $arr['z_requester']))));
$return = curlWrap("/tickets.json", $create);
?>