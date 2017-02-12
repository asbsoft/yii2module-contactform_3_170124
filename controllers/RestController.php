<?php

namespace asb\yii2\modules\contactform_3_170124\controllers;

use asb\yii2\common_2_170212\web\UserIdentity;
use asb\yii2\modules\contactform_3_170124\models\Contactform;
use asb\yii2\modules\contactform_3_170124\models\ContactformSearch;

use Yii;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class RestController extends ActiveController
{
    public $pageSize = 10; // default if not define in config

    protected $userIdentity;

    /**
     * @inheritdoc
     */
    public function __construct($id, $module, $config = [])
    {
        $this->modelClass = Contactform::className();

        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->userIdentity = new UserIdentity;

        if (!empty($this->module->params['pageSizeRest']) && intval($this->module->params['pageSizeRest']) > 0) {
            $this->pageSize = intval($this->module->params['pageSizeRest']);
        }
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['delete'], $actions['update']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $userId = $this->getUserId();
        $authManager = Yii::$app->authManager;
        $isAdmin = $authManager->checkAccess($userId, 'roleAdmin');
        if (!$isAdmin) {
            throw new ForbiddenHttpException('Access to data denied');
        }            
    }

    /**
     * @inheritdoc
     */
    public function prepareDataProvider()
    {
        $params = Yii::$app->request->queryParams;

        $userId = $this->getUserId();
        if (empty($userId)) return null;

        $modelSearch = new ContactformSearch();

        $dataProvider = $modelSearch->search($params);

        $page = empty($params['page']) ? 1 : intval($params['page']);
        if ($page == 0) $page = 1;
        $pager = $dataProvider->getPagination();
        $pager->pageSize = $this->pageSize;
        $pager->totalCount = $dataProvider->getTotalCount();
        $pager->page = $page - 1; //! from 0

        return $dataProvider;
    }

    /**
     * Get user id by access token.
     * @return integer|false user id or false if auth fail
     */
    public function getUserId()
    {
        $params = Yii::$app->request->queryParams;
        if (empty($params['access-token'])) {
            $result = false;
        } else {
            $uid = $this->userIdentity->findIdentityByAccessToken($params['access-token']);
            $result = empty($uid->id) ? false : $uid->id;
        }
        return $result;
    }

}
