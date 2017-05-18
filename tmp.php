<?php
$username='tim-zhong';
$password='Tim2374*';

$ch = curl_init();
curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/d3/d3/commits?per_page=200&sha=e6cab562e81d4847cc8e581f7ae638e86bac3b34');
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
//curl_setopt($ch, CURLOPT_POSTFIELDS, 'since=1&bar=2&baz=3');
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
$result=curl_exec ($ch);
curl_close ($ch);

$processed = [];
$result = json_decode($result);



foreach ($result as $r) {
	$sha = $r->sha;
	$committer = $r->commit->committer->name;
	$date = $r->commit->committer->date;
	array_push($processed, (object)array(
			'sha' => $sha,
			'committer' => $committer,
			'date' => $date,
		)
	);
}

//print_r($status_code);
echo "<pre>";
print_r($processed);
echo "</pre>";