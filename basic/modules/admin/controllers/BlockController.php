<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Block;
use app\models\BlockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlockController implements the CRUD actions for Block model.
 */
class BlockController extends AdminBaseController
{


    /**
     * Lists all Block models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Block model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['index']);
        /*
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
        */
    }

    /**
     * Creates a new Block model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Block();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (isset($_POST['title']) && is_array($_POST['title'])) {
                foreach($_POST['title'] as $lang => $val) $model->setTitle($lang, $val);
            }

            if (isset($_POST['content']) && is_array($_POST['content'])) {
                foreach($_POST['content'] as $lang => $val) $model->setContent($lang, $val);
            }

            $model->updateURLCode();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Block model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (isset($_POST['title']) && is_array($_POST['title'])) {
                foreach($_POST['title'] as $lang => $val) $model->setTitle($lang, $val);
            }

            if (isset($_POST['content']) && is_array($_POST['content'])) {
                foreach($_POST['content'] as $lang => $val) $model->setContent($lang, $val);
            }

            $model->updateURLCode();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Block model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Block model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Block the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Block::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
