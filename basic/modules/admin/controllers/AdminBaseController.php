<?php
/**
 * Created by PhpStorm.
 * User: Rogelio
 * Date: 10/28/2015
 * Time: 1:52 PM
 */

namespace app\modules\admin\controllers;


use app\modules\admin\components\AdminAccessControl;
use Yii;
use yii\web\Controller;

class AdminBaseController extends Controller
{

    public function behaviors()
    {
        return [
            [
                'class' => AdminAccessControl::className(),
            ],
        ];
    }

    public function getAdmin() {
        return Yii::$app->session->get('admin');
    }



}