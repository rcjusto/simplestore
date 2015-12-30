<?php

namespace app\modules\admin;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $layout = 'admin';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->errorHandler->errorAction = '/admin/default/error';
    }



}
