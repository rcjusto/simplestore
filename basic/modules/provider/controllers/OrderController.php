<?php

namespace app\modules\provider\controllers;

use app\models\OrderProducts;
use Yii;
use app\models\Order;
use app\models\OrderSearch;
use yii\web\NotFoundHttpException;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends ProviderBaseController
{


    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $searchModel->provider_id = $this->getProvider()->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateConsumed($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && isset($_REQUEST['order_products']) && is_array($_REQUEST['order_products'])) {
            foreach($_REQUEST['order_products'] as $op_id => $consumed) {
                /** @var OrderProducts $op */
                $op = OrderProducts::findOne($op_id);
                if (!is_null($op) && $op->order_id==$model->id) {
                    if ($op->consumed < min($op->quantity, $consumed)) {
                        $op->consumed_date = date('Y-m-d H:i:s');
                    }
                    if (isset($_REQUEST['order_consumed']) && is_array($_REQUEST['order_consumed']) && isset($_REQUEST['order_consumed'][$op_id]) && !empty($_REQUEST['order_consumed'][$op_id])) {
                        $op->consumed_date = $_REQUEST['order_consumed'][$op_id];
                    }
                    $op->consumed = min($op->quantity, $consumed);
                    $op->save();
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
