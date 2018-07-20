<?php

namespace dmstr\modules\publication\controllers;

use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\web\traits\AccessBehaviorTrait;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\HttpException;
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
     * @return array|Response
     */
    public function actionChangeItemStatus() {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $post = \Yii::$app->request->post();

            $code = 200;
            $errorMessage = null;
            
            
            if (!isset($post['itemId'])) {
                $code = 500;
                $errorMessage = \Yii::t('publication','Item ID not set');
            }
            
            $item = PublicationItem::findOne($post['itemId']);
            
            if ($item === null) {
                $code = 500;
                $errorMessage = \Yii::t('publication','Item not found');
            }
            
            $item->status = $item->status === PublicationItem::STATUS_PUBLISHED ? PublicationItem::STATUS_DRAFT : PublicationItem::STATUS_PUBLISHED;

            if (!$item->save()) {
                $code = 500;
                $errorMessage = \Yii::t('publication','Unable to save item');
            }

           return ['code' => $code,'errorMessage' => $errorMessage];
        }
        return $this->redirect(['/' . $this->module->id]);
    }
}
