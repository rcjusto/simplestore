<?php

namespace app\controllers;

use app\models\ItemCategorySearch;
use app\models\ItemNameSearch;
use app\models\Product;
use Yii;

class CatalogController extends BaseSiteController
{


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProduct()
    {
        $request = Yii::$app->request;
        $product = null;
        if ($code = $request->get('code')) {
            $product = Product::find()->where(['url_code' => $code])->one();
        }
        if (is_null($product) && $id = $request->get('id')) {
            $product = Product::findOne($id);
        }
        if (!is_null($product)) {
            return $this->render('product', ['model' => $product]);
        } else {

            return $this->goHome();
        }
    }

    public function actionCategory()
    {
        $params = array_merge(Yii::$app->request->get(), Yii::$app->request->post());
        $searchModel = new ItemCategorySearch();
        $dataProvider = $searchModel->search($params);

        if (!is_null($searchModel->getCategory())) {
            return $this->render('category', ['searchModel' => $searchModel, 'dataSource' => $dataProvider]);
        } else {
            return $this->goHome();
        }
    }

    public function actionSearch()
    {
        $params = array_merge(Yii::$app->request->get(), Yii::$app->request->post());
        $searchModel = new ItemNameSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('search', ['searchModel' => $searchModel, 'dataSource' => $dataProvider]);

    }


}
