<?php
namespace app\modules\admin\components;


use app\models\Admin;
use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;

class AdminAccessControl extends ActionFilter
{

    const IDENTITY_SESSION = 'admin';
    const IDENTITY_COOKIE = '_admin';

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        $admin = Yii::$app->session->get(self::IDENTITY_SESSION);
        if (!($admin instanceof Admin)) $admin = null;
        if (is_null($admin)) $admin = $this->tryToLoginFromCookie();
        if (is_null($admin) && $action->id!='login') {
            return Yii::$app->getResponse()->redirect(Url::to(['/admin/default/login']));
        }
        return parent::beforeAction($action);
    }

    private function tryToLoginFromCookie()
    {
        $value = Yii::$app->getRequest()->getCookies()->getValue(self::IDENTITY_COOKIE);
        if ($value === null) {
            return null;
        }

        $data = json_decode($value, true);
        if (count($data) !== 2 || !isset($data[0], $data[1])) {
            return null;
        }

        list ($username, $duration) = $data;

        $admin = Admin::find()->where(['email'=>$username])->one();
        if (!is_null($admin)) {
            Yii::$app->session->set(self::IDENTITY_SESSION, $admin);
        }
        return $admin;
    }


}