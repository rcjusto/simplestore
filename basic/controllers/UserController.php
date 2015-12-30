<?php

namespace app\controllers;

use app\models\DestinationForm;
use app\models\Order;
use app\models\ProfileForm;
use app\models\UserFavorites;
use app\models\UserShippingAddress;
use Yii;
use yii\data\ActiveDataProvider;

class UserController extends BaseSiteController
{

    public function actionIndex()
    {
        return $this->actionProfile();
    }

    public function actionOrders()
    {
        if (!is_null($user = $this->getLoggedUser())) {

            $data = new ActiveDataProvider([
                'query' => Order::find()->where(['user_id' => $user->id])->orderBy(['created' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]);

            return $this->render('orders', ['data' => $data]);
        } else {
            return $this->redirect(['site/register']);
        }
    }

    public function actionOrder($id)
    {
        if (!is_null($user = $this->getLoggedUser())) {
            /** @var Order $model */
            $model = Order::findOne($id);
            if (!is_null($model) && $model->user_id == $user->id) {
                if (isset($_REQUEST['output']) && $_REQUEST['output'] == 'print') {
                    $this->layout = 'print';
                    return $this->render('order_print', ['model' => $model]);
                } else {
                    return $this->render('order', ['model' => $model]);
                }
            } else {
                return $this->redirect(['user/orders']);
            }
        } else {
            return $this->redirect(['site/register']);
        }
    }

    public function actionReOrder($id)
    {
        if (!is_null($user = $this->getLoggedUser())) {
            /** @var Order $model */
            $model = Order::findOne($id);
            if (!is_null($model) && $model->user_id == $user->id) {
                $shopCart = $this->getShoppingCart();
                foreach ($model->orderProducts as $item) {
                    if (!is_null($product = $item->product))
                        $shopCart->addProduct($product, $item->quantity, '');
                }
                return $this->redirect(['shopcart/index']);
            } else {
                return $this->redirect(['user/orders']);
            }
        } else {
            return $this->redirect(['site/register']);
        }
    }

    public function actionProfile()
    {
        if (!is_null($user = $this->getLoggedUser())) {
            $profile = new ProfileForm($user);
            if (Yii::$app->request->isPost && $profile->load(Yii::$app->request->post()) && $profile->validate() && $profile->update($user)) {

            }
            return $this->render('profile', ['model' => $profile, 'user' => $user]);
        } else {
            return $this->redirect(['site/register']);
        }
    }

    public function actionDestinationDelete($id)
    {
        if (!is_null($user = $this->getLoggedUser())) {
            /** @var UserShippingAddress $model */
            $model = UserShippingAddress::findOne($id);
            if (!is_null($model) && $model->user_id == $user->id) {
                $model->delete();
            }
            return $this->redirect(['user/profile']);
        } else {
            return $this->redirect(['site/register']);
        }
    }

    public function actionDestination($id)
    {
        if (!is_null($user = $this->getLoggedUser())) {
            /** @var UserShippingAddress $model */
            $model = UserShippingAddress::findOne($id);
            if (!is_null($model) && $model->user_id == $user->id) {
                return $this->renderPartial('_destination', ['model' => $model]);
            } else if ($id == 0) {
                return $this->renderPartial('_destination', ['model' => null]);
            }
        }
        return null;
    }

    public function actionDestinationEdit($id)
    {
        if (!is_null($user = $this->getLoggedUser())) {
            /** @var UserShippingAddress $model */
            $model = UserShippingAddress::findOne($id);
            if (!is_null($model) && $model->user_id == $user->id) {

                $form = new DestinationForm($model);
                if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post()) && $form->update($model)) {
                    return $this->renderPartial('_destination', ['model' => $model]);
                }
                return $this->renderPartial('_destination_edit', ['model' => $form, 'id' => $id]);
            }
        }
        return null;
    }

    public function actionDestinationCreate()
    {
        if (!is_null($user = $this->getLoggedUser())) {
            /** @var UserShippingAddress $model */
            $model = new UserShippingAddress();
            $model->user_id = $user->id;
            $form = new DestinationForm($model);
            if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post()) && $form->update($model)) {
                return $this->renderPartial('_destination', ['model' => $model]);
            }
            return $this->renderPartial('_destination_edit', ['model' => $form, 'id' => 0]);
        }
        return null;
    }

    public function actionFavorites()
    {
        if (!is_null($user = $this->getLoggedUser())) {

            $data = new ActiveDataProvider([
                'query' => UserFavorites::find()->where(['user_id' => $user->id])->orderBy(['created' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('favorites', ['data' => $data]);
        } else {
            return $this->redirect(['site/register']);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/index']);
    }


}
