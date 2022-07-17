<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png">
<style>
@import url('https://fonts.googleapis.com/css2?family=Righteous&display=swap');
html {
  font-family: Righteous;
  background: #fff;
  margin: 0;
  padding: 0
}


</style>

</head>
<body>

<h1>HOICHOI FREE STREAM</h1>
<hr>
<?php
/*
$email = "test@gmail.com";
$password = "tes@12345";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://prod-api.viewlift.com/identity/signin?site=hoichoitv&deviceId=browser-6c83ee48-b374-5a1e-c1fe-8fbd579f1b7b",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\"email\":\"$email\",\"password\":\"$password\"}",
  CURLOPT_HTTPHEADER => array(
        "authority: prod-api.viewlift.com",
        "accept: application/json, text/plain,",
        "sec-ch-ua-mobile: ?0",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36",
        "x-api-key: dtGKRIAd7y3mwmuXGk63u3MI3Azl1iYX8w9kaeg3",
        "content-type: application/json;charset=UTF-8",
        "origin: https://www.hoichoi.tv/",
        "sec-fetch-site: cross-site",
        "sec-fetch-mode: cors",
        "sec-fetch-dest: empty",
        "referer: https://www.hoichoi.tv/",
        "accept-language: en-US,en-IN;q=0.9,en;q=0.8"
  ),
));

$result = curl_exec($curl);
curl_close($curl);

$hcauth =json_decode($result, true);
$auth = $hcauth['authorizationToken'];
echo $auth;
*/
$auth = "enter your auth token";
$url = $_GET["q"];
//echo $url;
if($url !=""){

$pid = str_replace('https://www.hoichoi.tv/', '/', $url);

echo $pid;
if (strpos($pid, 'movies') !== false){
$hlink ="https://prod-api-cached-2.viewlift.com/content/pages?path=$pid&site=hoichoitv&includeContent=true&moduleOffset=0&moduleLimit=4&languageCode=en&countryCode=IN";


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $hlink,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: $auth",
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);

$a1 =json_decode($response, true);

$id = $a1['modules'][1]['contentData'][0]['gist']['id'];


$hclink ="https://prod-api.viewlift.com/entitlement/video/status?id=$id&deviceType=web_browser&contentConsumption=web";

$xurl = curl_init();
curl_setopt_array($xurl, array(
  CURLOPT_URL => $hclink,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Accept: application/json, text/plain, */*",
    "Authorization: $auth",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
    "Origin: https://www.hoichoi.tv",
    "Host: prod-api.viewlift.com",
    "Referer: https://www.hoichoi.tv/",
    "Accept-Language: en-US,en;q=0.9",
    "Connection: keep-alive"
  ),
));
$result2 = curl_exec($xurl);
curl_close($xurl);

$hoichoi =json_decode($result2, true);
//echo $result2;
$title = $hoichoi['video']['gist']['title'];
$des = $hoichoi['video']['gist']['description'];
$lang = $hoichoi['video']['gist']['languageCode'];

$srt = $hoichoi['video']['contentDetails']['closedCaptions'][0]['url']; //srt subtitle for vtt change 0 to 1

$pro = $hoichoi['video']['gist']['posterImageUrl']; // movie poster
$land = $hoichoi['video']['gist']['videoImageUrl']; // Landscape Poster

$hls = $hoichoi['video']['streamingInfo']['videoAssets']['hls']; // auto resolution all qualities included
$h270 = $hoichoi['video']['streamingInfo']['videoAssets']['mpeg'][0]['url']; // 270p
$h360 = $hoichoi['video']['streamingInfo']['videoAssets']['mpeg'][0]['url']; // 360p
$h720 = $hoichoi['video']['streamingInfo']['videoAssets']['mpeg'][0]['url']; // 720p


 $apii = array("id" => $id, "lang" => $lang, "title" => $title, "description" => $des, "landscape" => $land, "portrait" => $pro, "hls" => $hls, "270p" => $h270, "360p" => $h360, "720p" => $h720, "subtitle" => $srt);

 $api =json_encode($apii, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

echo $api;
echo "<form method='GET' action='/play.php'>
<input type='hidden' name='c' value='$hls'>
<input class='submit' type='submit' value='Submit'>
</form>";
}
elseif (strpos($pid, 'shows') !== false){

    $hlink ="https://prod-api-cached-2.viewlift.com/content/pages?path=$pid&site=hoichoitv&includeContent=true&moduleOffset=0&moduleLimit=4&languageCode=default&countryCode=IN&userState=eyJzdGF0ZSI6WyJzdWJzY3JpYmVkIl0sImNvbnRlbnRGaWx0ZXJJZCI6bnVsbH0%3D";


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $hlink,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: $auth",
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $auth;
$a2 =json_decode($response, true);

$json = $a2['modules'][1]['contentData'][0]['seasons'][0]['episodes'];
foreach ($json as $key => $value) {
    $oo[] = $value['id'];
    echo $value['id'];
    echo "<br>";
}









}
}
else{
  $ex= array("error" => "Something went wrong, Check URL and Parameters !");
  $error =json_encode($ex);

  echo $error;
}

?>
