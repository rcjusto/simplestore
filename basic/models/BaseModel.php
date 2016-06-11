<?php

namespace app\models;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{

    public function to_array() {
        return $this->getAttributes();
    }

    public function from_array($arr) {
        $this->setAttributes($arr);
        return $this->save();
    }
}