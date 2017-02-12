<?php

namespace asb\yii2\modules\contactform_3_170124\controllers;

use asb\yii2\common_2_170212\controllers\BaseAdminController;
use asb\yii2\modules\contactform_3_170124\models\Contactform;
use asb\yii2\modules\contactform_3_170124\models\ContactformSearch;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Contactform model.
 */
class AdminController extends BaseAdminController
{
    public $pageSizeAdmin = 10;
    public $maxlenBodyInAdminList = 100;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!empty($this->module->params['pageSizeAdmin']) && intval($this->module->params['pageSizeAdmin']) > 0) {
            $this->pageSizeAdmin = intval($this->module->params['pageSizeAdmin']);
        }
        if (!empty($this->module->params['maxlenBodyInAdminList']) && intval($this->module->params['maxlenBodyInAdminList']) > 0) {
            $this->maxlenBodyInAdminList = intval($this->module->params['maxlenBodyInAdminList']);
        }
    }
/*
    public function actionIndex()
    {
        return $this->redirect(['list']);
    }
*/
    /**
     * Lists all Contactform models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new ContactformSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pager = $dataProvider->getPagination();
        $pager->pageSize = $this->pageSizeAdmin;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contactform model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Contactform model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contactform model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contactform the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contactform::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
