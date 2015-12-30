<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mail".
 *
 * @property integer $id
 * @property string $created
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property integer $status
 * @property string $error
 */
class Mail extends \yii\db\ActiveRecord
{

    const STATUS_SENT = 10;
    const STATUS_ERROR_FATAL = 5;
    const STATUS_PENDING = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'to'], 'required'],
            [['created'], 'safe'],
            [['body', 'error'], 'string'],
            [['status'], 'integer'],
            [['from'], 'string', 'max' => 255],
            [['to', 'subject'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'from' => 'From',
            'to' => 'To',
            'subject' => 'Subject',
            'body' => 'Body',
            'status' => 'Status',
            'statusDesc' => 'Status',
            'error' => 'Error',
        ];
    }

    public function beforeValidate()
    {
        if (empty($this->created)) $this->created = date('Y-m-d H:i:s');
        if (empty($this->status)) $this->status = 0;
        return parent::beforeValidate();
    }

    public function getStatusDesc() {
        if ($this->status==Mail::STATUS_SENT) return 'Sent';
        else if ($this->status==Mail::STATUS_ERROR_FATAL) return 'Fatal Error';
        else if ($this->status==Mail::STATUS_PENDING) return 'Pending';
        else return 'Error ('.$this->status.' attempts)';
    }



}
