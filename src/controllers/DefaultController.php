<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationItem;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;

class DefaultController extends Controller
{
    /**
     * @param $categoryId
     * @return string
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->get('categoryId');
        $limit = Yii::$app->request->get('limit');

        if ($param === null) {
            throw new BadRequestHttpException(Yii::t('publication', 'Category ID must be set'));
        }

        if (!$this->checkParam($param)) {
            throw new InvalidConfigException(Yii::t('publication', 'Invalid config for param category id'));
        }

        if ($limit !== null && (!is_numeric($limit) || $limit < 1)) {
            throw new InvalidConfigException(Yii::t('publication', 'Invalid config for param limit'));
        }

        return $this->render('index', ['categoryId' => $param, 'limit' => $limit]);
    }

    /**
     * @param $itemId
     * @return string
     * @throws HttpException
     */
    public function actionDetail($itemId)
    {
        $item = PublicationItem::find()->andWhere(['app_dmstr_publication_item.id' => $itemId])->published()->one();

        if ($item === null) {
            throw new HttpException(404, \Yii::t('publication', 'Publication item not found'));
        }

        $item->setScenario('crud');

        return $this->render('detail', ['item' => $item]);

    }

    private function checkParam($param)
    {
        if (!\is_array($param)) {
            if (is_numeric($param)) {
                return true;
            }
            if ($param === 'all') {
                return true;
            }
            return false;
        }
        foreach ($param as $item) {
            if (!is_numeric($item)) {
                return false;
            }
        }
        return true;
    }
}
