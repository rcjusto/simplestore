<?php


namespace app\modules\provider\controllers;


use app\models\Provider;
use app\modules\provider\components\ProviderAccessControl;
use Yii;
use yii\web\Controller;

class ProviderBaseController extends Controller
{


    public function behaviors()
    {
        return [
            [
                'class' => ProviderAccessControl::className(),
            ],
        ];
    }

    /**
     * @return Provider
     */
    public function getProvider() {
        return Yii::$app->session->get('provider');
    }

}