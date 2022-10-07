<?php


function authorize_user() {
        $urlToExecute = 'https://account.withings.com/oauth2_user/authorize2';
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => "adb8803b7e8a1e7686a66f0d77f177d36d5995b5533f2a5f7f2a9a15e05502eb",
            'state' => 'STATE',
            'scope' => 'user.metrics',
            'redirect_uri' => 'http://localhost/ProjetMansour/authorize.php',
            'mode' => 'demo'
        ]);
        $urlToExecute .= "?$query";
        return $urlToExecute;
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

<script>
    window.open("<?= authorize_user(); ?>", "OAuth2PopUp",  "popup=1");
</script>



</body>
</html>