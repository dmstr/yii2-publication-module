<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationItem;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @param string $categoryId
     *
     * @throws BadRequestHttpException
     * @return string
     */
    public function actionIndex($categoryId = PublicationHelper::ALL)
    {
        $tagId = Yii::$app->request->get('tagId', PublicationHelper::ALL);
        $limit = Yii::$app->request->get('limit');

        if (!$this->checkParam($categoryId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param category id'));
        }

        if ($limit !== null && (!is_numeric($limit) || $limit < 1)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param limit'));
        }

        return $this->render('index', ['categoryId' => $categoryId,'tagId' => $tagId, 'limit' => $limit]);
    }

    /**
     * @param $tagId
     *
     * @throws BadRequestHttpException
     * @return string
     */
    public function actionTag($tagId = PublicationHelper::ALL)
    {
        $categoryId = Yii::$app->request->get('categoryId', PublicationHelper::ALL);
        $limit = Yii::$app->request->get('limit');


        if (!$this->checkParam($tagId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param tag id'));
        }

        if (!$this->checkParam($categoryId)) {
            throw new BadRequestHttpException(Yii::t('publication', 'Invalid config for param category id'));
        }

        if ($limit !== null && (!is_numeric($limit) || $limit < 1)) {
            throw new BadRequestHttpException(422, Yii::t('publication', 'Invalid config for param limit'));
        }

        return $this->render('tag', ['tagId' => $tagId, 'limit' => $limit, 'categoryId' => $categoryId]);
    }

    private function checkParam($param)
    {
        if (!\is_array($param)) {
            return is_numeric($param) || $param === PublicationHelper::ALL;
        }
        foreach ($param as $item) {
            if (!is_numeric($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $itemId
     *
     * @throws HttpException
     * @return string
     */
    public function actionDetail($itemId)
    {
        $item = PublicationItem::find()->andWhere([PublicationItem::tableName() . '.id' => $itemId])->published()->one();

        if ($item === null) {
            throw new NotFoundHttpException(\Yii::t('publication', 'Publication item not found'));
        }

        $item->setScenario('crud');

        return $this->render('detail', ['item' => $item]);

    }
}
