<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model app\models\ContactForm */
    /* @var $ajaxReceiver string|false id of DOM-element for receive AJAX responce */

    use asb\yii2\modules\contactform_3_170124\controllers\MainController;

    use asb\yii2\common_2_170212\assets\CommonAsset;

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap\ActiveForm;
    use yii\captcha\Captcha;
    use yii\captcha\CaptchaAction;


    $tc = $this->context->tc;

    $submitButtonId = 'contactforn-submit-button';
    $contactFormId  = 'contact-form';
    $submitImgId    = 'submit-indicator';
    $captchaImgId   = 'contact-captcha';
    $submitCaptchaLoaderId = 'captcha-loader';

    $formAction = Url::toRoute([$this->context->action->id]);
    $captchaAction = '//' . $this->context->uniqueId . '/' . MainController::$captchaActionId;

    $this->title = Yii::t($tc, 'Contact form');
    $this->params['breadcrumbs'][] = $this->title;

    $commonAsset = CommonAsset::register($this);

    $messageClass = 'alert alert-warning contact-form-ajax-message';
    if (Yii::$app->session->hasFlash('contactFormSubmitted')) {
        $messageClass = 'alert alert-success contact-form-message';
        $message = Yii::t($tc, 'Thank you for contacting us');
    }

?>
<div class="site-contact">
    <?php if (!$ajaxReceiver): ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php endif; ?>

    <?php if ($message): ?>

        <div class="<?= $messageClass ?>">
            <?= $message ?>
        </div>

    <?php else: ?>

        <p>
	        </p>

        <div class="row">
            <div class="<?= $ajaxReceiver ? 'contact-form-ajax' : 'col-lg-5 contact-form' ?>">

                <?php $form = ActiveForm::begin([
                    'id' => $contactFormId,
                  //'enableClientValidation' => false, // turn off JS-validation
                ]); ?>

                    <?php if ($ajaxReceiver) echo Html::hiddenInput('ajax-receiver', $ajaxReceiver); ?>

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
                        'imageOptions' => [
                            'id' => $captchaImgId,
                            'title' => Yii::t($tc, 'Click to refresh code'),
                        ],
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t($tc, 'Submit'), [
                            'id' => $submitButtonId,
                            'name' => $submitButtonId,
                            'class' => 'btn btn-primary',
                        ]) ?>
                        <?= Html::img("{$commonAsset->baseUrl}/img/wait.gif", [
                            'id' => $submitImgId,
                            'class' => 'collapse',
                        ]) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>

<?php

    $this->registerJs("
        jQuery(document).ready(function() {
            setTimeout(function () {
                jQuery('#{$submitCaptchaLoaderId}').hide(); // always hide by timeout
            }, 9000);
            jQuery('#{$submitCaptchaLoaderId}').show();
        });
        jQuery('#{$captchaImgId}').bind('load', function() { // sometimes not run
            jQuery('#{$submitCaptchaLoaderId}').hide();
        });
        jQuery('#{$captchaImgId}').bind('click', function() {
            jQuery('#{$captchaImgId}').attr('src', '{$commonAsset->baseUrl}/img/wait.gif');
        });
    ");

    if ($ajaxReceiver) {
        $this->registerJs("
            var submitted = false;
            var valid = false;
            jQuery('#{$contactFormId}').bind('beforeSubmit', function (e) {
                valid = true;
            });
            jQuery('#{$contactFormId}').bind('submit', function(event) {
                if (submitted || !valid) {
                    return false;
                }
                submitted = true;  // to avoid form be submitted twice

                event.preventDefault();
                jQuery('#{$submitButtonId}').hide();
                jQuery('#{$submitImgId}').show();

                //jQuery('#{$ajaxReceiver}').load('{$formAction}', jQuery('#contact-form').serialize()); // send by GET need POST
                //jQuery('#{$ajaxReceiver}').load('{$formAction}', this.elements); // JS TypeError
//*
                var elements = this.elements;
                for (var data = {}, max = elements.length, i = 0; i < max; i++) {
                    var obj = elements[i];
                    if('checkbox' == obj.type) {
                        data[obj.name] = obj.checked;
                    } else if('radio' == obj.type && obj.checked) {
                        data[obj.name] = obj.value;
                    } else {
                        data[obj.name] = obj.value;
                    }//alert(obj.name + '=' + data[obj.name])
                }
                jQuery('#{$ajaxReceiver}').load('{$formAction}', data);
/**/                
                return false;
            });
        ");
    }

?>
