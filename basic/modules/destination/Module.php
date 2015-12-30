<?php

namespace app\modules\destination;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\destination\controllers';
    public $layout = 'destination';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->errorHandler->errorAction = '/destination/default/error';
    }
}
