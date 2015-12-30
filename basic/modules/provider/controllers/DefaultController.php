<?php

namespace app\modules\provider\controllers;

use app\models\AdminLoginForm;
use app\models\Order;
use app\models\OrderProducts;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

class DefaultController extends ProviderBaseController
{

    public function actionIndex()
    {
        $provider = $this->getProvider();

        $query = OrderProducts::find();
        $query->leftJoin('product', 'product.id=order_products.product_id');
        $query->leftJoin('order', 'order.id=order_products.order_id');
        $query->andFilterWhere(['order.status'=>Order::STATUS_APPROVED]);
        $query->andFilterWhere(['product.provider_id'=>$provider->id]);
        $query->andWhere('order_products.quantity>order_products.consumed');
        $query->orderBy(['order.id'=>SORT_DESC, 'order_products.id'=>SORT_ASC]);
        $query->limit(10);

        // top sellers
        $sql = "select product_id, sum(quantity) as cant from order_products left join simplestore.order on order.id=order_products.order_id left join product on product.id=order_products.product_id where order.status=:approved and product.provider_id=:prov group by product_id order by cant desc limit :limit";
        $products = Yii::$app->db->createCommand($sql, [':approved'=>Order::STATUS_APPROVED, ':prov'=>$provider->id, ':limit'=>10])->queryAll();


        return $this->render('index', ['pending' => $query->all(), 'products'=>$products]);
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        $model = new AdminLoginForm();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && !is_null($provider = $model->loginProvider())) {
            Yii::$app->session->set('provider', $provider);
            return $this->redirect(['/provider/default/index']);
        }
        return $this->render('login', ['model'=>$model]);
    }

    public function actionLogout() {
        Yii::$app->session->remove('provider');
        return $this->redirect(['/provider/default/index']);
    }

    public function actionProfile() {

        $provider = $this->getProvider();

        if (Yii::$app->request->isPost && isset($_REQUEST['password'])) {
            if (!empty(Yii::$app->request->post('password'))) {
                $provider->password = Yii::$app->request->post('password');
                $provider->save();
                Yii::$app->session->setFlash('password-info', Yii::t('app','Password updated successfully'));
            } else {
                Yii::$app->session->setFlash('password-error', Yii::t('app','Password cannot be empty'));
            }
        }

        return $this->render('profile', [
            'provider' => $provider,
        ]);

    }


    public function actionError() {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = Yii::t('yii', 'An internal server error occurred.');
        }
        return $this->render('error',[
            'name' => $name,
            'message' => $message,
        ]);
    }

}
