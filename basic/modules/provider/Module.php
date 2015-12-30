<?php

namespace app\modules\provider;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\provider\controllers';
    public $layout = 'provider';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->errorHandler->errorAction = '/provider/default/error';
    }
}
