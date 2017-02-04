<?php

/* @var $this yii\web\View */
/* @var $model asb\yii2\modules\contactform_3_170124\models\Contactform */

    use asb\yii2\modules\contactform_3_170124\helpers\Formatter;

    use yii\helpers\Html;
    use yii\widgets\DetailView;

    $module = $this->context->module;
    $tc = $module->tcModule;
    $formatter = new Formatter;

    $this->title = Yii::t($tc, 'Message from') . " {$model->name}, " . $formatter->asDatetime($model->create_at);
    $this->params['breadcrumbs'][] = ['label' => Yii::t($tc, 'Contacts admin'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;

?>
<div class="contactform-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-right">
        <?php /* Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) */ ?>
        <?= Html::a(Yii::t($tc, 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t($tc, 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => Formatter::className()],
        'attributes' => [
            'id',
            'name',
            'email:email',
            'subject',
            'body:ntext',

          //'user_id',
          //'userName',
          //'user_id:username',
            [ 'label' => Yii::t($tc, 'Registrated user'),
              'value' => $model->userName . (empty($model->user_id) ? '' : " (#{$model->user_id})"),
            ],

            'ip',
            'browser',
            'create_at',
        ],
    ]) ?>

</div>
