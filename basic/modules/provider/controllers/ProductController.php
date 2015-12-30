<?php

namespace app\modules\provider\controllers;

use app\models\ImageManager;
use app\models\ProductImagesForm;
use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends ProviderBaseController
{


    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $searchModel->provider_id = $this->getProvider()->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /*
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
        */

        return $this->redirect(['index']);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (isset($_POST['name']) && is_array($_POST['name'])) {
                foreach($_POST['name'] as $lang => $val) $model->setName($lang, $val);
            }

            if (isset($_POST['description']) && is_array($_POST['description'])) {
                foreach($_POST['description'] as $lang => $val) $model->setDescription($lang, $val);
            }

            if (isset($_POST['information']) && is_array($_POST['information'])) {
                foreach($_POST['information'] as $lang => $val) $model->setInformation($lang, $val);
            }

            $model->updateURLCode();

            return $this->redirect(['update', 'id' => $model->id, 'panel' => 'images']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (isset($_POST['name']) && is_array($_POST['name'])) {
                foreach($_POST['name'] as $lang => $val) $model->setName($lang, $val);
            }

            if (isset($_POST['description']) && is_array($_POST['description'])) {
                foreach($_POST['description'] as $lang => $val) $model->setDescription($lang, $val);
            }

            if (isset($_POST['information']) && is_array($_POST['information'])) {
                foreach($_POST['information'] as $lang => $val) $model->setInformation($lang, $val);
            }

            $model->updateURLCode();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'panel' => isset($_REQUEST['panel']) ? $_REQUEST['panel'] : ''
            ]);
        }
    }

    /**
     * Add images to an existing Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionImagesAdd($id)
    {
        $model = new ProductImagesForm();

        if (Yii::$app->request->post()) {
            $model->listFile = UploadedFile::getInstance($model, 'listFile');
            $model->detailFile = UploadedFile::getInstance($model, 'detailFile');
            $model->upload($id);
        }
        return $this->redirect(['update','id' => $id,'panel'=>'images']);
    }

    public function actionImageDelete($id)
    {
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        if (!empty($name)) {
            ImageManager::deleteProductImage($name);
        }
        return $this->redirect(['update','id' => $id,'panel'=>'images']);
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $provider = $this->getProvider();
        if (($model = Product::findOne($id)) !== null && $model->provider_id==$provider->id) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
