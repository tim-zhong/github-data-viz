<style>
span{
	font-weight: 600;
	color:#990000;
}
</style>
<?php
date_default_timezone_set('America/Toronto');

$orgname = "facebook";
$repo_name = "react";
$batch_size = 100;
$cur_date = '2017-05-18T23:06:29Z';

$processed = [];

function clean_files_array($f){
	$newf = (object)array(
		'filename' => $f->filename,
		'status' => $f->status,
		'additions' => $f->additions,
		'deletions' => $f->deletions,
		'raw_url' => $f->raw_url
	);
	return $newf;
}

function get_data($date){
	global $orgname;
	global $repo_name;
	global $batch_size;
	$username='tim-zhong';
	$password='Tim2374*';
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/$orgname/$repo_name/commits?per_page=$batch_size&until=$date");
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, 'since=1&bar=2&baz=3');
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$result=curl_exec ($ch);
	curl_close ($ch);

	$result = json_decode($result);

	return $result;
}

function get_files_changed($sha){
	global $orgname;
	global $repo_name;

	$username='tim-zhong';
	$password='Tim2374*';

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/$orgname/$repo_name/commits/$sha");
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	//curl_setopt($ch, CURLOPT_POSTFIELDS, 'since=1&bar=2&baz=3');
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$result=curl_exec ($ch);
	curl_close ($ch);

	$result = json_decode($result);

	$files = array_map('clean_files_array', $result->files);

	return $files;
}

$counter = 0;
for($i = 1; $i <= 90; $i++){
	echo("getting data until $cur_date. ");
	$result = get_data($cur_date);
	foreach ((array)$result as $r) {
		$sha = $r->sha;
		$committer = $r->commit->committer->name;
		$date = $r->commit->committer->date;
		$files = get_files_changed($sha);

		array_push($processed, (object)array(
				'sha' => $sha,
				'committer' => $committer,
				'date' => $date,
				'files' => $files
				)
			);
	}

	$n = count($result);
	if($n){
		$cur_date = $result[$n-1]->commit->committer->date;
		$cur_date = strtotime($cur_date) - 1; // minus 1 second to avoid duplicate
		$cur_date = gmdate('Y-m-d\TH:i:s\Z', $cur_date);

		echo("<br/>DONE (<span>$n/$batch_size</span> entries fetched)<br/>");
		$counter += $n;
	} else {
		break;
	}
}
echo("<br/>----------------------------------------------------------------------<br/>");
echo("Finished: <span>$counter</span> entries fecthed in total");

$filename = "$orgname.$repo_name.json";

echo("<br/>Writing results to <span>$filename</span>.");
$myfile = fopen($filename, "w") or die("Unable to open file!");
$txt = json_encode($processed);
ftruncate($myfile, 0);
fwrite($myfile, $txt);
fclose($myfile);
echo("<br/>Writing complete.");




// print_r($status_code);
// echo "<pre>";
// print_r($processed);
// echo "</pre>";