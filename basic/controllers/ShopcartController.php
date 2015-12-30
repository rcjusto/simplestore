<?php

namespace app\controllers;

use app\components\StoreUtils;
use app\models\CreditCardForm;
use app\models\LoginForm;
use app\models\Order;
use app\models\Product;
use app\models\User;
use app\models\UserAuthorizeNet;
use app\models\UserShippingAddress;
use Yii;
use yii\web\Response;

class ShopcartController extends BaseSiteController
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCheckout()
    {
        if (!$this->getShoppingCart()->hasItems() || is_null($this->getLoggedUser()))
            return $this->render('index');

        return $this->render('checkout');
    }

    public function actionCartReload()
    {
        return $this->renderPartial('//parts/_top_shop_cart');
    }

    public function actionPlaceOrder()
    {

        $shopCart = $this->getShoppingCart();
        $user = $this->getLoggedUser();

        if (!$shopCart->hasItems())
            return $this->render('index');

        $destination_id = Yii::$app->request->post('destination_id');
        if (empty($destination_id)) {
            return $this->render('checkout', ['error' => Yii::t('app', 'Select a destination')]);
        }

        /** @var UserShippingAddress $destination */
        $destination = UserShippingAddress::findOne($destination_id);
        if (is_null($destination) || $destination->user_id != $user->id) {
            return $this->render('checkout', ['error' => Yii::t('app', 'Select a destination')]);
        }

        $order = null;
        if ($shopCart->getTotal() > 0) {

            $payment_id = Yii::$app->request->post('payment_id');
            if (empty($payment_id)) {
                return $this->render('checkout', ['error' => Yii::t('app', 'Select a payment method')]);
            }

            /** @var UserAuthorizeNet $uan */
            $uan = UserAuthorizeNet::findOne($payment_id);
            if (is_null($uan) || $uan->user_id != $user->id) {
                return $this->render('checkout', ['error' => Yii::t('app', 'Select a payment method')]);
            }

            $order = Order::generateFromCart($shopCart, $user, $destination);
            $order->message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
            $order->status = Order::STATUS_PENDING;
            $order->save();
            $uan->postPayment($order);

        } else {

            $order = Order::generateFromCart($shopCart, $user, $destination);
            $order->status = Order::STATUS_APPROVED;
            $order->message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
            $order->save();

        }

        if (is_null($order)) {
            return $this->redirect(['index']);
        } else {

            //generate emails
            $order->generateEmailToBuyer();
            $order->generateEmailToAdmin();
            if ($order->status == Order::STATUS_APPROVED) {
                $order->generateEmailToDestination();
                $order->generateEmailToProviders();
            }
            StoreUtils::sendPendingEmails();

            $shopCart->items = [];
            $user->resetUserShopCart();
            return $this->render('order_result', ['model' => $order]);
        }
    }

    public function actionTestResult($id) {
        $order = Order::findOne($id);
        return $this->render('order_result', ['model' => $order]);
    }


    public function actionAddProduct($id)
    {
        /** @var Product $model */
        $model = Product::findOne($id);
        $qty = isset($_REQUEST['quantity']) && is_numeric($_REQUEST['quantity']) && $_REQUEST['quantity'] > 0 ? $_REQUEST['quantity'] : 1;
        $data = isset($_REQUEST['data']) && !empty($_REQUEST['data']) ? $_REQUEST['data'] : '';
        $this->getShoppingCart()->addProduct($model, $qty, $data);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $message = Yii::t('app', '<span class="glyphicon glyphicon-ok"></span> Product <strong>{pname}</strong> added to Shopping Cart', ['pname' => $model->getName(Yii::$app->language)]);
            return ['result' => 'ok', 'message' => $message];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionUpdateItem($index)
    {
        if (isset($_REQUEST['quantity']) && is_numeric($_REQUEST['quantity']) && $_REQUEST['quantity'] > 0) {
            $this->getShoppingCart()->updateQuantity($index, $_REQUEST['quantity']);
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['result' => 'ok', 'message' => Yii::t('app', 'Product quantity updated in Shopping Cart')];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionDelItem($index)
    {
        $this->getShoppingCart()->delItem($index);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['result' => 'ok', 'message' => Yii::t('app', '<span class="glyphicon glyphicon-remove"></span> Product deleted from Shopping Cart')];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->user->isGuest) {
                if ($model->login() && !is_null($this->getLoggedUser())) {
                    $this->getLoggedUser()->restoreUserShopCart($this->getShoppingCart());
                }
            }
        }
        return $this->render('index', ['login' => $model]);
    }

    public function actionForgotPassword()
    {
        $username = Yii::$app->request->post('username');
        /** @var User $model */
        $model = User::find()->where(['email' => $username])->one();
        if (!is_null($model)) {
            $model->generateEmailForgotPassword();
            StoreUtils::sendPendingEmails();
        }
        return $this->renderPartial('_forgot_password', ['model' => $model]);
    }

    public function actionDestinationSave()
    {

        $id = Yii::$app->request->get('id');

        $model = null;
        if (!empty($id))
            $model = UserShippingAddress::findOne($id);
        if (is_null($model))
            $model = new UserShippingAddress();

        $model->user_id = $this->getLoggedUser()->id;
        if ($model->load(Yii::$app->request->get()) && $model->save()) {
            $this->getShoppingCart()->destination = $model->id;
            $model = null;
        }
        return $this->renderPartial('_sc_destination', ['model' => $model]);
    }

    public function actionDestinationEdit($id)
    {
        /** @var UserShippingAddress $model */
        $model = UserShippingAddress::findOne($id);
        if (!is_null($model) && $model->user_id != $this->getLoggedUser()->id) {
            $model = null;
        }
        return $this->renderPartial('_sc_destination', ['model' => $model]);
    }

    public function actionDestinationDelete($id)
    {
        /** @var UserShippingAddress $model */
        $model = UserShippingAddress::findOne($id);
        if (!is_null($model) && $model->user_id == $this->getLoggedUser()->id) {
            $model->delete();
        }
        return $this->renderPartial('_sc_destination', ['model' => null]);
    }

    public function actionDestinationChange()
    {
        $newID = Yii::$app->request->get('destination_id');
        if (!empty($newID)) {
            $this->getShoppingCart()->destination = $newID;
        }
        return $this->renderPartial('_sc_destination', ['model' => null]);
    }

    public function actionPaymentSave()
    {

        $id = Yii::$app->request->get('id');

        $model = null;
        if (!empty($id))
            $model = UserAuthorizeNet::findOne($id);
        if (is_null($model))
            $model = new UserAuthorizeNet();

        $form = new CreditCardForm();

        $model->user_id = $this->getLoggedUser()->id;
        if ($form->load(Yii::$app->request->get()) && $form->validate() && $model->sendToAuthorize($form) && $model->save()) {
            $this->getShoppingCart()->payment_method = $model->id;
            $form = null;
            $id = null;
        }
        return $this->renderPartial('_sc_payment', ['model' => $form, 'id' => $id]);
    }

    public function actionPaymentDelete($id)
    {
        /** @var UserAuthorizeNet $model */
        $model = UserAuthorizeNet::findOne($id);
        if (!is_null($model) && $model->user_id == $this->getLoggedUser()->id && $model->deleteProfile()) {
            $model->delete();
        }
        return $this->renderPartial('_sc_payment', ['model' => null, 'id' => null]);
    }

    public function actionPaymentChange()
    {
        $newID = Yii::$app->request->get('payment_id');
        if (!empty($newID)) {
            $this->getShoppingCart()->payment_method = $newID;
        }
        return $this->renderPartial('_sc_payment', ['model' => null, 'id' => null]);
    }


}
