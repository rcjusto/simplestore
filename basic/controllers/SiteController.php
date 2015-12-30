<?php

namespace app\controllers;

use app\components\StoreUtils;
use app\models\Block;
use app\models\CommentForm;
use app\models\Property;
use app\models\RegisterForm;
use Yii;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\Response;

class SiteController extends BaseSiteController
{


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main';
        return $this->render('index');
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ['result' => 'error'];

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if (\Yii::$app->user->isGuest) {
                if ($model->login()) {
                    if (!is_null($this->getLoggedUser()))
                        $this->getLoggedUser()->restoreUserShopCart($this->getShoppingCart());
                    $result = ['result' => 'ok'];
                }
            } else {
                $result = ['result' => 'ok'];
            }
        }
        return $result;
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $back = isset($_REQUEST['back']) ? $_REQUEST['back'] : '';

        if (!is_null($this->getLoggedUser())) {
            if (!empty($back)) $this->redirect($back);
            else $this->redirect(['user/profile']);
        }

        $this->layout = 'main';
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {

            StoreUtils::sendPendingEmails();

            if (!empty($back)) {
                return $this->redirect($back);
            }
            return $this->goHome();
        }
        return $this->render('register', [
            'model' => $model,
            'back' => $back,
        ]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            StoreUtils::sendPendingEmails();
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionComment()
    {
        $model = new CommentForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('commentFormSubmitted');
            StoreUtils::sendPendingEmails();
            return $this->refresh();
        }
        return $this->render('comment', [
            'model' => $model,
        ]);
    }

    public function actionPage()
    {
        $request = Yii::$app->request;
        $block = null;
        if ($code = $request->get('code')) {
            $block = Block::find()->where(['url_code'=>$code])->one();
            if (is_null($block))
                $block = Block::findOne($code);
        }

        if (!is_null($block)) {
            return $this->render('page',['model'=>$block]);
        } else {
            return $this->goHome();
        }
    }

    public function actionLang($lang) {
        if (!empty($lang)) {
            $session = Yii::$app->session;
            $session->set(self::LANGUAGE_KEY, $lang);
            Yii::$app->language = $lang;
        }
        return $this->redirect(Yii::$app->request->referrer);
    }


}
