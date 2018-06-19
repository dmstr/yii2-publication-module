<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\web\traits\AccessBehaviorTrait;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;

class CrudController extends Controller
{

    use AccessBehaviorTrait;

    /**
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
