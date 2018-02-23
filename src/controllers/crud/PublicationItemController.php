<?php
/**
 * /app/src/../runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\models\crud\PublicationItem;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * This is the class for controller "PublicationItemController".
 */
class PublicationItemController extends \dmstr\modules\publication\controllers\crud\base\PublicationItemController
{
    /**
     *
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update'],
                'rules' => [

                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actionCreate()
    {


        $model = new PublicationItem();

        $publicationCategoryId = null;
        if (isset(\Yii::$app->request->get()[$model->formName()]['category_id'])) {
            $publicationCategoryId = \Yii::$app->request->get()[$model->formName()]['category_id'];
        }
        $model->setContentSchemaByCategoryId($publicationCategoryId);
        $model->setTeaserSchemaByCategoryId($publicationCategoryId);

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\HttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->setContentSchemaByCategoryId($model->category_id);
        $model->setTeaserSchemaByCategoryId($model->category_id);

        if ($model->load($_POST) && $model->save()) {

            return $this->redirect(Url::previous());
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
