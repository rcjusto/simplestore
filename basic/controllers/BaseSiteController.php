<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/29/2015
 * Time: 4:53 PM
 */

namespace app\controllers;


use app\models\Property;
use app\models\ShoppingCart;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\Session;

class BaseSiteController extends Controller
{

    const SHOPCART_KEY = 'shopcart';
    const LANGUAGE_KEY = 'language';

    public $layout = 'column_lc';
    private $_logged_user = null;

    public function init()
    {
        $session = Yii::$app->session;
        if ($session->has(self::LANGUAGE_KEY)) {
            Yii::$app->language = $session->get(self::LANGUAGE_KEY);
        }
        parent::init();
    }

    public function beforeAction($action)
    {
        $storeName = Property::getPropertyValue('store_name' , '');

        Yii::$app->view->title = Property::getPropertyValue('store_title', $storeName);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => Property::getPropertyValue('store_description', $storeName)
        ], 'description');

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => Property::getPropertyValue('store_keywords', $storeName)
        ], 'keywords');

        return parent::beforeAction($action);
    }


    /**
     * @return null|User
     */
    public function getLoggedUser() {
        if (!Yii::$app->user->isGuest) {
            if (is_null($this->_logged_user)) {
                $this->_logged_user = User::findOne(Yii::$app->user->id);
            }
            return $this->_logged_user;
        } else {
            return null;
        }
    }

    /**
     * @return ShoppingCart
     */
    public function getShoppingCart() {
        $shopCart = null;
        /** @var Session $session */
        $session = Yii::$app->session;
        if ($session->has(self::SHOPCART_KEY)) {
            $shopCart = $session->get(self::SHOPCART_KEY);
        }
        if (is_null($shopCart)) {
            $shopCart = new ShoppingCart();
            $session->set(self::SHOPCART_KEY, $shopCart);
        }
        return $shopCart;
    }

}