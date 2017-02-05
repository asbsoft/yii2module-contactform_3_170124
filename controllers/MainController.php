<?php

namespace asb\yii2\modules\contactform_3_170124\controllers;

//use yii\web\Controller as BaseController;
use asb\yii2\controllers\BaseController;

use asb\yii2\modules\contactform_3_170124\models\Contactform;

use yii\captcha\CaptchaAction;

use Yii;

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
        $this->tc = $this->tc = $module->tcModule;
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

    public function actionForm()
    {
        $model = new Contactform();
        $model->scenario = $model::SCENARIO_FRONTFORM;

        if ($model->load(Yii::$app->request->post()))
        {
            $result = $model->validate();
            if($result && $model->save(false)) {
                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->refresh();
            }
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

}
