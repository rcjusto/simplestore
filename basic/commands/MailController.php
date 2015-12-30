<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Mail;
use Yii;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MailController extends Controller
{


    public function actionIndex()
    {
        Yii::error("RCJ - Esto entro aqui");
        /** @var Mail[] $list */
        $list = Mail::find()->where('status < :ss and created <= :date', [':ss' => Mail::STATUS_ERROR_FATAL, ':date'=>date('Y-m-d H:i:s')])->all();
        Yii::error("RCJ - encontro: " . count($list));
        print "Procesing: " . count($list) . " mails\n";
        foreach ($list as $mail)
            $this->send($mail, Yii::$app->params['adminEmail']);

    }

    /**
     * @param $mail Mail
     * @param $alt_from
     */
    private function send($mail, $alt_from)
    {
        $res = Yii::$app->mailer->compose()
            ->setFrom(!empty($mail->from) ? $mail->from : $alt_from)
            ->setTo($mail->to)
            ->setSubject($mail->subject)
            ->setHtmlBody($mail->body)
            ->send();

        if ($res) {
            $mail->status = Mail::STATUS_SENT;
            $mail->save();
        } else {
            $mail->status += 1;
            if ($mail->status > Mail::STATUS_ERROR_FATAL) $mail->status = Mail::STATUS_ERROR_FATAL;
            $mail->save();
        }
    }




}
