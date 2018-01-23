<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationCategory;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;

class CrudController extends Controller
{
    /**
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
