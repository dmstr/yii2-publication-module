<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationItem;
use yii\web\Controller;
use yii\web\HttpException;

class DefaultController extends Controller
{
    /**
     * @param $categoryId
     * @return string
     */
    public function actionIndex($categoryId)
    {
        return $this->render('index',['categoryId' => $categoryId]);
    }

    public function actionDetail($itemId)
    {
        $item = PublicationItem::findOne($itemId);

        if ($item === null) {
            throw new HttpException(404, \Yii::t('publication','Publication item not found'));
        }

        $item->setScenario('crud');

        return $this->render('detail',['item' => $item,'showTitle' => \Yii::$app->request->get('showTitle')]);

    }
}
