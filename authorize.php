<?php

$_SESSION['code'] = $_GET['code'];


function getToken() {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://wbsapi.withings.net/v2/oauth2');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
        'action' => 'requesttoken',
        'grant_type' => 'authorization_code',
        'client_id' => 'adb8803b7e8a1e7686a66f0d77f177d36d5995b5533f2a5f7f2a9a15e05502eb',
        'client_secret' => 'f3302b0f7526a15248de62c8f04f8fca2b8b9ce3915a26ca9b5a09ad69b2fbef',
        'code' => $_SESSION['code'],
        'redirect_uri' => 'http://localhost/ProjetMansour/authorize.php'
    ]));

    $rsp = curl_exec($ch);
    curl_close($ch);
    $resf = json_decode($rsp, true);
    return $resf;
}


function getMeasure() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://wbsapi.withings.net/measure");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $_SESSION['token'],
            "cache-control: no-cache"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
            'action' => 'getmeas',
            'meastype' => 1,
        ]));

        $rsp = curl_exec($ch);
        curl_close($ch);
        $resf = json_decode($rsp, true);
        return $resf;
}



?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
</head>
<body>

<p>authorize succ : code : <?php echo $_SESSION['code']; ?></p>


<?php 

if($_GET['code']){
    $_SESSION['token'] = getToken()['body']['access_token'];
    if(isset($_SESSION["token"])){
        $measures = getMeasure()['body']['measuregrps'];
        $max = 0;
        $obj;
        foreach ($measures as $key => $value) {
            if($value['modified'] > $max){
                $max = $value['modified'];
                $obj = $value;
            }
        }
        
        echo "Measure with highest Timestamp is : <br>";
        echo "id : " . $obj['grpid'] . "<br>";
        echo "attrib : " . $obj['attrib'] . "<br>";
        echo "date : " . date('d-m-Y H:i:s', $obj['date'])  . "<br>";
        echo "created : " . date('d-m-Y H:i:s', $obj['created'])  . "<br>";
        echo "modified : " . date('d-m-Y H:i:s', $obj['modified'])  . "<br>";
        echo "category : " . $obj['category'] . "<br>";
        echo "last weight measurement(s) : value = " . $obj['measures'][0]['value'] . ", type = " . $obj['measures'][0]['type'] . ", unit = " . $obj['measures'][0]['unit'] . ", algo = " . $obj['measures'][0]['algo'] . ", fm = " . $obj['measures'][0]['fm'] . "<br>";
        echo "comment : " . $obj['comment'] . "<br>";
    }
}

?>


</body>
</html>