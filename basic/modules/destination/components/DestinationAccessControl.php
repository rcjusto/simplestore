<?php
namespace app\modules\destination\components;


use app\models\Admin;
use app\models\DestinationAccount;
use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

class DestinationAccessControl extends ActionFilter
{

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        $destination = Yii::$app->session->get('destination');
        if (!($destination instanceof DestinationAccount)) $destination = null;
        if (is_null($destination) && $action->id!='login' && $action->id!='forgot-password') {
            return Yii::$app->getResponse()->redirect(Url::to(['/destination/default/login']));
        }
        return parent::beforeAction($action);
    }

}