<?php

use yii\rest\UrlRule as RestUrlRule;

/**
 * @var $routeConfig array of parameters, contain key 'urlPrefix', 'sublink', etc.
 * @return array in format need for urlManager->addRules(...)
 */

if (empty($routeConfig['sublink'])) $routeConfig['sublink'] = 'rest-contact-form';

// controller(s) sublink(s): link part => controllerUid, result link will be ".../$urlPrefix/$sublink/..."
$controller = [
    $routeConfig['sublink'] => $routeConfig['moduleUid'] . '/' . 'rest',
];

return [
    'enablePrettyUrl' => true,
    'rest-routes-' . $routeConfig['moduleUid'] => [
        'class' => RestUrlRule::className(),
        'controller' => $controller,
        'prefix' => $routeConfig['urlPrefix'],
        'patterns' => [
            'GET,HEAD {id}' => 'view',
            'GET,HEAD' => 'index',
            '{id}' => 'options',
            '' => 'options',
        ],
    ],
];
