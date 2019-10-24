<?php


$cognitive_sub_api_key = 'b16e3b875d234c64b89c2f6d6c0950e3';

//Generating access token
function get_access_token( $sub_api_key ){
  $headers =
  "Content-length: 0\r\n";

  $options = array (
      'http' => array (
          'header' => $headers,
          'method' => 'POST',
          'content'=>"",
      ),
  );

  $context  = stream_context_create ( $options );
  $result = file_get_contents ("https://eastus.api.cognitive.microsoft.com/sts/v1.0/issueToken?Subscription-Key=".$sub_api_key, false, $context);
  return $result;
}

//Making valid token
$token  = "Bearer " . get_access_token( $cognitive_sub_api_key );

$endpoint = "https://api.cognitive.microsofttranslator.com";

$path = "/transliterate?api-version=3.0";

$params = "&language=ja&fromScript=jpan&toScript=latn";
// Transliterate "good afternoon".
if (!function_exists('com_create_guid')) {
  function com_create_guid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}
function Transliterate ($host, $path, $tokenGenerated, $params, $content) {
    $headers = "Content-type: application/json\r\n" .
        "Content-length: " . strlen($content) . "\r\n" .
        "Authorization: $tokenGenerated\r\n" .
        "X-ClientTraceId: " . com_create_guid() . "\r\n";
    // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
    // http://php.net/manual/en/function.stream-context-create.php
    $options = array (
        'http' => array (
            'header' => $headers,
            'method' => 'POST',
            'content' => $content
        )
    );

        $context  = stream_context_create ($options);
    $result = file_get_contents ($host . $path . $params, false, $context);
    return $result;
}



$text = "犬はパンを食べています";

$requestBody = array (
    array (
        'Text' => $text,
    ),
);

$content = json_encode($requestBody);
$result = Transliterate ($endpoint, $path, $token, $params, $content);


$json = json_encode(json_decode($result), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
echo $json;
?>
