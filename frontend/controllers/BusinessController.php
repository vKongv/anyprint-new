<?php

namespace app\controllers;

class BusinessController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
