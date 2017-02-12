<?php
/**
    @var $this yii\web\View
    @var $form yii\bootstrap\ActiveForm
    @var $model app\models\ContactForm
*/
    use asb\yii2\modules\contactform_3_170124\controllers\MainController;

    use asb\yii2\common_2_170212\assets\CommonAsset;

    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\captcha\Captcha;
    use yii\captcha\CaptchaAction;

    $tc = $this->context->tc;

    $captchaAction = '//' . $this->context->uniqueId . '/' . MainController::$captchaActionId;

    $this->title = Yii::t($tc, 'Contact form');
    $this->params['breadcrumbs'][] = $this->title;

    $commonAsset = CommonAsset::register($this);

?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            <?= Yii::t($tc, 'Thank you for contacting us'); ?>
        </div>

    <?php else: ?>

        <p>
	        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                    'enableClientValidation' => false, // turn off JS-validation: CAPTCHA JS-validation problem
                ]); ?>

                    <?= $form->field($model, 'name', [
                        'labelOptions' => ['label' => Yii::t($tc, 'Your name')],
                    ]) ?>

                    <?= $form->field($model, 'email', [
                        'labelOptions' => ['label' => Yii::t($tc, 'E-mail')],
                    ]) ?>

                    <?= $form->field($model, 'subject', [
                        'labelOptions' => ['label' => Yii::t($tc, 'Subject')],
                    ]) ?>

                    <?= $form->field($model, 'body', [
                        'labelOptions' => ['label' => Yii::t($tc, 'Body')],
                        ])->textArea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode', [
                        'labelOptions' => ['label' => Yii::t($tc, 'Enter code here')],
                        ])->widget(Captcha::className(), [
                        'template' => include(__DIR__ . '/captcha.php'),
                        'captchaAction' => $captchaAction,
                      //'captchaAction' => [$captchaAction, [CaptchaAction::REFRESH_GET_VAR => 1]], // after every post new captcha
                        'imageOptions' => [
                            'id' => 'contact-captcha',
                            'title' => Yii::t($tc, 'Click to refresh code'),
                        ],
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t($tc, 'Submit'), [
                            'class' => 'btn btn-primary',
                            'name' => 'contact-button',
                        ]) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>

<?php

    $this->registerJs("
        jQuery('#contact-captcha').bind('click', function() {
            jQuery('#contact-captcha').attr('src', '{$commonAsset->baseUrl}/img/wait.gif');
        });
    ");

?>
