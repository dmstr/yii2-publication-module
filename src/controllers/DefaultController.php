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

        if ($param === null) {
            throw new BadRequestHttpException(Yii::t('user', 'Category ID must be set'));
        }

        if (!$this->checkParam($param)) {
            throw new InvalidConfigException(Yii::t('user', 'Invalid config for param category id'));
        }

        return $this->render('index', ['categoryId' => $param]);
    }

    /**
     * @param $itemId
     * @return string
     * @throws HttpException
     */
    public function actionDetail($itemId)
    {
        $item = PublicationItem::findOne($itemId);

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
