<?php

// route without prefix => controller/action without current (and parent) module(s) IDs
$result = [
    'form'    => 'main/form',
    'captcha' => 'main/captcha',
  //'?'       => 'main/index', // without URL-normalizer
  //''        => 'main/index', // with URL-normalizer
];

$mgr = Yii::$app->urlManager;
$normalizeTrailingSlash = !empty($mgr->normalizer->normalizeTrailingSlash) && $mgr->normalizer->normalizeTrailingSlash;//var_dump($normalizeTrailingSlash);
if ($normalizeTrailingSlash) {
    $result[''] = 'main/index';
} else {
    $result['?'] = 'main/index';
}//var_dump($result);exit;

return $result;
