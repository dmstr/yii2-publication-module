<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationCategory;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;

class CategoryController extends Controller
{
    /**
     * @param $categoryId
     * @throws HttpException
     */
    public function actionIndex($categoryId)
    {
        $publicationCategory = PublicationCategory::findOne($categoryId);

        if ($publicationCategory === null) {
            throw new HttpException(404, \Yii::t('publication','Category not found'));
        }

        VarDumper::dump($publicationCategory->getTeaserWidgetTemplate()->one(),4,1);
        VarDumper::dump($publicationCategory->getContentWidgetTemplate()->one(),4,1);

    }
}
