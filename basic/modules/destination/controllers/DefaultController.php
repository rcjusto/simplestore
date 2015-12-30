<?php

namespace app\modules\destination\controllers;

use app\components\StoreUtils;
use app\models\AdminLoginForm;
use app\models\DestinationAccount;
use app\models\Order;
use app\models\OrderProducts;
use app\models\OrderShipping;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\web\HttpException;

class DefaultController extends DestinationBaseController
{


    public function actionIndex()
    {
        $destination = $this->getDestinationAccount();

        // pending orders
        $query = OrderProducts::find()->where('quantity>consumed');
        $query->leftJoin('order_shipping', "order_products.order_id = order_shipping.order_id");
        $query->andFilterWhere(['order_shipping.shipping_email'=>$destination->email]);
        $query->orderBy(['order_products.order_id'=>SORT_DESC, 'order_products.id'=>SORT_DESC]);

        return $this->render('index', ['pending'=>$query->all()]);
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        $model = new AdminLoginForm();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if (!is_null($destination = $model->loginDestination())) {
                $destination->last_login = date('Y-m-d H:i:s');
                $destination->save();
                Yii::$app->session->set('destination', $destination);

                return $this->redirect(['/destination/default/index']);
            } else {
                Yii::$app->session->setFlash('login-error', Yii::t('app','Invalid Username or Password'));
            }
        }
        return $this->render('login', ['model'=>$model]);
    }

    public function actionForgotPassword()
    {
        $this->layout = 'login';
        $model = new AdminLoginForm();
        $show = 'login';
        $username = Yii::$app->request->post('username');
        /** @var DestinationAccount $destination */
        $destination = DestinationAccount::find()->where(['email'=>$username])->one();
        if (!is_null($destination)) {
            $destination->generateEmailForgotPassword();
            StoreUtils::sendPendingEmails();
            $model->username = $destination->email;
            Yii::$app->session->setFlash('general-info', Yii::t('app','An email with the information you requested was successfully sent to {email}',['email'=>$destination->email]));
        } else {
            $show = 'password';
            Yii::$app->session->setFlash('password-error', Yii::t('app','The email address you provided could not be found'));
        }
        return $this->render('login', ['model'=>$model, 'show'=>$show]);
    }

    public function actionLogout() {
        Yii::$app->session->remove('destination');
        return $this->redirect(['/destination/default/index']);
    }

    public function actionOrders() {

        $destination = $this->getDestinationAccount();

        $query = Order::find();
        $query->andFilterWhere(['status'=>Order::STATUS_APPROVED]);
        $query->leftJoin('order_shipping', "order.id = order_shipping.order_id");
        $query->andFilterWhere(['order_shipping.shipping_email' => $destination->email]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('orders', ['dataProvider' => $dataProvider]);
    }

    public function actionOrder($id) {
        /** @var Order $model */
        $model = Order::findOne($id);
        if (!is_null($model)) {
            if (!is_null($model->orderShipping)) {
                if ($model->orderShipping->shipping_email != $this->getDestinationAccount()->email) {
                    $model = null;
                }
            } else {
                $model = null;
            }
        }
        if (isset($_REQUEST['output']) && $_REQUEST['output']=='print') {
            $this->layout = 'print';
            return $this->render('order_print', ['model' => $model]);
        } else {
            return $this->render('order', ['model' => $model]);
        }
    }

    public function actionProfile() {

        $destination = $this->getDestinationAccount();

        $lastOrderShipping = OrderShipping::find()->where(['shipping_email'=>$destination->email])->orderBy(['order_id'=>SORT_DESC])->one();

        if (Yii::$app->request->isPost && isset($_REQUEST['password'])) {
            if (!empty(Yii::$app->request->post('password'))) {
                $destination->password = Yii::$app->request->post('password');
                $destination->save();
                Yii::$app->session->setFlash('password-info', Yii::t('app','Password updated successfully'));
            } else {
                Yii::$app->session->setFlash('password-error', Yii::t('app','Password cannot be empty'));
            }
        }

        return $this->render('profile', [
            'dataProvider' => $this->getBuyersDP($destination),
            'destination' => $destination,
            'shipping' => $lastOrderShipping
        ]);

    }

    public function actionLang($lang) {
        if (!empty($lang)) {
            $session = Yii::$app->session;
            $session->set(self::LANGUAGE_KEY, $lang);
            Yii::$app->language = $lang;
        }
        return $this->redirect(Yii::$app->request->referrer);
    }



    private function getBuyersDP($destination) {
        $query = User::find();
        $query->innerJoin('order', "order.user_id = user.id");
        $query->leftJoin('order_shipping', "order.id = order_shipping.order_id");
        $query->andFilterWhere(['order_shipping.shipping_email' => $destination->email]);
        $query->distinct();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'billing_first_name' => SORT_ASC,
                    'billing_last_name' => SORT_ASC,
                ],
            ],
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
