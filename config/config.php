<?php

    use asb\yii2\common_2_170212\base\UniApplication;
    use asb\yii2\modules\contactform_3_170124\controllers\AdminController;

    //use yii\rest\UrlRule as RestUrlRule;
    use asb\yii2\modules\contactform_3_170124\helpers\RestUrlRule;


    $adminUrlPrefix = empty(Yii::$app->params['adminPath']) ? '' : Yii::$app->params['adminPath'] . '/';
    $type = empty(Yii::$app->type) ? false : Yii::$app->type;
    
    /** Default module config */
    return [
        'routesConfig' => [ // type => prefix | array(config)
            'main' => $type == UniApplication::APP_TYPE_BACKEND ? false : [
                'urlPrefix'      => 'contactform',
                'startLinkLabel' => 'Contact us',
            ],
            'admin' => $type == UniApplication::APP_TYPE_FRONTEND ? false : [
                'urlPrefix' => $adminUrlPrefix . 'contactform-back',
                'startLink' => [
                    'label' => 'Contactform messages v3',
                  //'link'  => '', // default
                    'action' => 'admin/index',
                ],
            ],
            'rest' => [
                'class' => RestUrlRule::className(),
                'urlPrefix' => 'contactform-api',
                'sublink'   => 'contact-msg',
                'startLink' => [
                    'label'  => 'Contacts API',
                    'action' => 'rest/index',
                ],
            ],
        ],
    ];
