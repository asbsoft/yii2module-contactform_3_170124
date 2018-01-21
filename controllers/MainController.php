<?php

namespace asb\yii2\modules\contactform_3_170124\controllers;

//use yii\web\Controller as BaseController;
use asb\yii2\common_2_170212\controllers\BaseController;
use asb\yii2\modules\contactform_3_170124\models\Contactform;

use Yii;
use yii\captcha\CaptchaAction;

class MainController extends BaseController
{
    public static $captchaActionId = 'captcha';
    public static $captchaMinLength = 4;
    public static $captchaMaxLength = 6;

    public $tc;

    public function init()
    {
        parent::init();

        $module = $this->module;
        $this->tc = $module->tcModule;
    }

    public function actions()
    {
        return [
            static::$captchaActionId => [
                'class' => CaptchaAction::ClassName(),
                'testLimit' => 1, // how many times should the same CAPTCHA be displayed
                'minLength' => static::$captchaMinLength, // minimum length for randomly generated word
                'maxLength' => static::$captchaMaxLength, // maximum length for randomly generated word
                'transparent' => true, // use transparent background
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['form']);
    }

    /**
     * @param string|false $ajaxReceiver id of DOM-element for receive AJAX responce
     */
    public function actionForm($ajaxReceiver = false)
    {
        $message = '';
        $isAjax = Yii::$app->request->isAjax;
        $model = new Contactform();
        $model->scenario = $model::SCENARIO_FRONTFORM;

        $post = Yii::$app->request->post();
        if ($model->load($post))
        {
            $result = $model->validate();
            if($result && $model->save(false)) {
                $message = Yii::t($this->tc, 'Thank you for contacting us');
                if (!$isAjax) {
                    Yii::$app->session->setFlash('contactFormSubmitted');
                    return $this->refresh();
                }
            }
        }
        if ($isAjax) {
            $ajaxReceiver = $post['ajax-receiver'];
        }
        
        $render = $ajaxReceiver ? ($isAjax ? 'renderAjax' : 'renderPartial') : 'render';
        return $this->$render('contact', [
            'model' => $model,
            'ajaxReceiver' => $ajaxReceiver,
            'message' => $message,
        ]);
    }

}
