<?php

use yii\helpers\Html;

$loader = Html::img($commonAsset->baseUrl . '/img/wait.gif', [
    'id' => $submitCaptchaLoaderId,
    'class' => 'collapse',
]);

return <<<EOT
    <div class="row">
        <div class="col-lg-3">$loader{image}</div>
        <div class="col-lg-6">{input}</div>
    </div>
EOT;
?>