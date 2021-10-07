<?php


namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\assets\PublicationAttachAssetBundle;
use dmstr\modules\publication\controllers\crud\actions\Attach;
use dmstr\modules\publication\controllers\crud\actions\DeleteAttachment;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemXTag;
use dmstr\modules\publication\models\crud\PublicationTag;
use dmstr\modules\publication\models\crud\search\PublicationItem as PublicationItemSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


/**
 * This is the class for controller "PublicationItemController".
 */
class PublicationItemController extends BaseController
{
    public $model = PublicationItem::class;
    public $searchModel = PublicationItemSearch::class;

    /**
     * {@inheritdoc}
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        // if set use CKEditor configurations from settings module else use default configuration.
        $defaultConfig = '{
          "height": "400px",
          "toolbar": [
            ["Format"],
            ["Link", "Image", "Table", "-", "NumberedList", "BulletedList", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
            ["Source"],
            "/",
            ["Bold", "Italic", "Underline", "StrikeThrough", "-", "RemoveFormat", "-", "Undo", "Redo", "-", "Paste", "PasteText", "PasteFromWord", "-", "Cut", "Copy", "Find", "Replace", "-", "Outdent", "Indent", "-", "Print"]
          ]
        }';
        $json = \Yii::$app->settings->getOrSet('ckeditor.config', $defaultConfig, 'widgets', 'object');
        $ckeditorConfiguration = $json->scalar ?? $defaultConfig;
        $script = "window.CKCONFIG = {$ckeditorConfiguration};";
        \Yii::$app->view->registerJs($script, \yii\web\View::POS_HEAD);
        return parent::beforeAction($action);
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['delete-tag-attachment'] = DeleteAttachment::class;

        unset($actions['create'], $actions['update']);

        return $actions;
    }

    /**
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new PublicationItem();

        $publicationCategoryId = null;
        if (isset(\Yii::$app->request->get()[$model->formName()]['category_id'])) {
            $publicationCategoryId = \Yii::$app->request->get()[$model->formName()]['category_id'];
        }
        $model->setContentSchemaByCategoryId($publicationCategoryId);
        $model->setTeaserSchemaByCategoryId($publicationCategoryId);

        $model->ref_lang = Yii::$app->language;

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            if (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = $e->errorInfo[2] ?? $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render($this->action->id, ['model' => $model]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidParamException
     * @throws \yii\web\HttpException
     */
    public function actionUpdate($id)
    {
        /** @var PublicationItem $model */
        $model = PublicationItem::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('publication', 'Publication item does not exist'));
        }

        $model->setContentSchemaByCategoryId($model->category_id);
        $model->setTeaserSchemaByCategoryId($model->category_id);

        if ($model->load($_POST) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    public function actionAttach($id)
    {
        /** @var PublicationItem $model */
        $model = PublicationItem::findOne($id);

        if ($model->ref_lang !== Yii::$app->language) {
            throw new HttpException(500, Yii::t('publication', 'You are not allowed to do that.'));
        }

        if ($model === null) {

            throw new NotFoundHttpException(Yii::t('publication', 'Publication item does not exist'));
        }

        $attachedTags = $model->tags;

        $otherTagsQuery = PublicationTag::find()->where(['NOT IN', 'id', ArrayHelper::map($attachedTags, 'id', 'id')]);
        $qLangs = [Yii::$app->language];
        if (!empty(\Yii::$app->params['fallbackLanguages'][\Yii::$app->language])) {
            $qLangs[] = \Yii::$app->params['fallbackLanguages'][\Yii::$app->language];
        }
        $otherTagsQuery->andWhere(['ref_lang' => $qLangs]);

        $otherTags = $otherTagsQuery->all();

        PublicationAttachAssetBundle::register($this->view);

        return $this->render($this->action->id, [
            'model' => $model,
            'attachedTags' => $attachedTags,
            'otherTags' => $otherTags
        ]);
    }

    public function actionAttachTags()
    {
        if (\Yii::$app->request->isAjax) {

            $itemId = \Yii::$app->request->post('itemId');

            if (!is_numeric($itemId)) {
                return false;
            }

            /** @var Transaction $transaction */
            $transaction = \Yii::$app->db->beginTransaction();
            PublicationItemXTag::deleteAll(['item_id' => $itemId]);

            foreach ((array)\Yii::$app->request->post('tagIds') as $tagId) {
                /** @var ActiveRecord $bikeXTag */
                $itemXTag = new PublicationItemXTag(['item_id' => $itemId, 'tag_id' => $tagId]);

                if (!$itemXTag->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }
        return $this->redirect('index');
    }
}
