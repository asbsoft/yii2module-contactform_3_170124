<?php

namespace asb\yii2\modules\contactform_3_170124\helpers;

use yii\rest\UrlRule;

class RestUrlRule extends UrlRule
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->patterns = [
            'GET,HEAD {id}' => 'view',
            'GET,HEAD' => 'index',
            '{id}' => 'options',
            '' => 'options',
        ];
    }

}
