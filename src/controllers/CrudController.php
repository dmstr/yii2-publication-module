<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\web\traits\AccessBehaviorTrait;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    /**
     * @return Response
     *
     * @throws ErrorException
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionChangeItemStatus()
    {
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();

            $errorMessage = null;
            if (!isset($post['entryId'])) {
                throw new InvalidConfigException(\Yii::t('publication', 'Item ID not set'));
            }

            $item = PublicationItem::findOne($post['entryId']);

            if ($item === null) {
                throw new NotFoundHttpException(\Yii::t('publication', 'Item not found'));
            }

            $item->status = $item->status === PublicationItem::STATUS_PUBLISHED ? PublicationItem::STATUS_DRAFT : PublicationItem::STATUS_PUBLISHED;
            $item->release_date = $item->publicationItemMetas[0]->release_date ?? date('Y-m-d');

            $item->scenario = 'meta';
            if (!$item->save()) {
                throw new ErrorException(\Yii::t('publication', 'Unable to save item'));
            }

            return $this->goBack('/' . $this->module->id . '/' . $this->id . '/publication-item/index');
        }
        return $this->redirect(['/' . $this->module->id]);
    }
}
