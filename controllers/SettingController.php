<?php

namespace app\controllers;

use app\models\Schedule;

class SettingController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $schedule = new Schedule();
        return $this->render('index', [
            'schedule' => $schedule
        ]);
    }

}
