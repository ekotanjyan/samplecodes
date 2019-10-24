<?php
function transliteration($source_language_code, $target_language_gode, $word)
{
  $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=".$source_language_code."&tl=".$target_language_gode."&dt=t&q=".urlencode ($word)."&dt=rm";
  $content = file_get_contents($url);
  $result = json_decode($content);
  $arrTransliterations = $result[0][1];
  //var_dump($arrTransliterations);
  if (!empty($arrTransliterations)) {
    foreach($arrTransliterations as $strValue)
    {
      if($strValue && !empty($arrTransliterations))
      {
        echo $strValue . "<br>";
      }
    }
  }

}

transliteration('en', 'ja', 'dog is in hat');
transliteration('en', 'zh', 'cat wearing a hat');
transliteration('en', 'ar', 'car is red');
