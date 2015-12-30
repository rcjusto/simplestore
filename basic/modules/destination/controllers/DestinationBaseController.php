<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/28/2015
 * Time: 1:52 PM
 */

namespace app\modules\destination\controllers;


use app\models\DestinationAccount;
use app\modules\destination\components\DestinationAccessControl;
use Yii;
use yii\web\Controller;

class DestinationBaseController extends Controller
{

    const LANGUAGE_KEY = 'language';

    public function behaviors()
    {
        return [
            [
                'class' => DestinationAccessControl::className(),
            ],
        ];
    }

    public function init()
    {
        $session = Yii::$app->session;
        if ($session->has(self::LANGUAGE_KEY)) {
            Yii::$app->language = $session->get(self::LANGUAGE_KEY);
        }
        parent::init();
    }

    /**
     * @return DestinationAccount
     */
    public function getDestinationAccount() {
        return Yii::$app->session->get('destination');
    }

}