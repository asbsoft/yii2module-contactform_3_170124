<?php

/* @var $this yii\web\View */
/* @var $searchModel asb\yii2\modules\contactform_3_170124\models\ContactformSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

    use asb\yii2\modules\contactform_3_170124\helpers\Formatter;
    use asb\yii2\modules\contactform_3_170124\models\UserModel;

    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;


    $assets = $this->context->module->registerAsset('AdminAsset', $this); // inherited

    $module = $this->context->module;
    $tc = $module->tcModule;
    UserModel::$tc = $tc;

    $this->title = Yii::t($tc, 'Contacts admin');
    $this->params['breadcrumbs'][] = $this->title;

    $usersFilter = ArrayHelper::merge([
            0 => Yii::t($tc, '(anonymous)'),
        ], UserModel::usernameList()
    );

?>
<div class="contactform-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => Formatter::className()],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [ 'attribute' => 'user_id',
              'label' => Yii::t($tc, 'User'),
              'format' => 'username',
              'filter' => $usersFilter,
              'filterInputOptions' => [
                  'class' => 'form-control',
                  'id' => null,
                  'prompt' => Yii::t($tc, '-all-'),
              ],
            ],
            'email:email',
            'subject',

            //'body:ntext',
            [ 'attribute' => 'body',
              //'format' => 'ntext',
              'format' => ['ntextCut', $this->context->maxlenBodyInAdminList],
              'header' => Yii::t($tc, 'Text'), // only text, no sort button
            ],

            'ip',
            // 'browser',
            [ 'attribute' => 'create_at',
              //'format' => 'datetime', // show in locale format => need special datepicker for search filter
            ],
            'id',

            [ 'class' => 'yii\grid\ActionColumn',
            //'template' => '{view} {update} {delete}', // default
              'template' => '{view} {delete}',
            ],
        ],
    ]); ?>

</div>
