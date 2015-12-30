<?php
namespace app\modules\provider\components;


use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

class ProviderAccessControl extends ActionFilter
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
        $provider = Yii::$app->session->get('provider');
        if (!($provider instanceof \app\models\Provider)) $provider = null;
        if (is_null($provider) && $action->id!='login') {
            return Yii::$app->getResponse()->redirect(Url::to(['/provider/default/login']));
        }
        return parent::beforeAction($action);
    }

}