<?php

namespace app\modules\admin\controllers;


use app\models\AdminLoginForm;
use app\models\Order;
use app\models\Property;
use app\models\User;
use app\modules\admin\components\AdminAccessControl;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\Cookie;
use yii\web\HttpException;

class DefaultController extends AdminBaseController
{
    public function actionIndex()
    {
        // last orders
        $data['orders'] = Order::find()->orderBy(['id'=>SORT_DESC])->limit(10)->all();

        // last registered users
        $data['users'] = User::find()->orderBy(['id'=>SORT_DESC])->limit(10)->all();

        // top sellers
        $sql = "select product_id, sum(quantity) as cant from order_products left join simplestore.order on order.id=order_products.order_id where order.status=:approved group by product_id order by cant desc limit :limit";
        $data['products'] = Yii::$app->db->createCommand($sql, [':approved'=>Order::STATUS_APPROVED, ':limit'=>10])->queryAll();

        return $this->render('index', $data);
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        $model = new AdminLoginForm();
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && !is_null($admin = $model->loginAdmin())) {
            Yii::$app->session->set(AdminAccessControl::IDENTITY_SESSION, $admin);
            $this->sendIdentityCookie($admin->email, $model->rememberMe ? 3600*24*30 : 0);
            return $this->redirect(['/admin/default/index']);
        }
        return $this->render('login', ['model'=>$model]);
    }

    public function actionLogout() {
        Yii::$app->session->remove(AdminAccessControl::IDENTITY_SESSION);
        Yii::$app->getResponse()->getCookies()->remove(AdminAccessControl::IDENTITY_COOKIE);
        return $this->redirect(['/admin/default/index']);
    }

    public function actionProperties() {
        if (isset($_REQUEST['properties']) && is_array($_REQUEST['properties'])) {
            foreach($_REQUEST['properties'] as $propID => $propValue) {
                Property::setPropertyValue($propID, $propValue);
            }
        }
        return $this->render('properties' );
    }

    public function actionTestMail() {
        $res = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo('rcjusto@yahoo.com')
            ->setSubject('Testing Email')
            ->setHtmlBody('Sending a test email...')
            ->send();
        echo ($res) ? 'Email Sent' : 'Something failed';
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

    protected function sendIdentityCookie($username, $duration)
    {
        $cookie = new Cookie(['name' => AdminAccessControl::IDENTITY_COOKIE, 'httpOnly' => true]);
        if ($duration>0) {
            $cookie->value = json_encode([
                $username,
                $duration,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $cookie->expire = time() + $duration;
            Yii::$app->getResponse()->getCookies()->add($cookie);
        } else {
            Yii::$app->getResponse()->getCookies()->remove($cookie->name);
        }
    }

}
