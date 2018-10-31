<?php
/**
 * /app/src/../runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace dmstr\modules\publication\controllers\crud;

use dmstr\bootstrap\Tabs;
use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\search\PublicationItem as PublicationItemSearch;
use yii\helpers\Url;


/**
 * This is the class for controller "PublicationItemController".
 */
class PublicationItemController extends \dmstr\modules\publication\controllers\crud\base\PublicationItemController
{

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
        $ckeditorConfiguration = isset($json->scalar) ? $json->scalar : $defaultConfig;
        $script = "window.CKCONFIG = {$ckeditorConfiguration};";
        \Yii::$app->view->registerJs($script, \yii\web\View::POS_HEAD);
        return parent::beforeAction($action);
    }


    /**
     * @return mixed|string
     */
    public function actionIndex()
    {
        $searchModel = new PublicationItemSearch;
        $dataProvider = $searchModel->search($_GET);
        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('../../default/crud/publication-item-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
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

        try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage();
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

    public function actionDelete($id)
    {
        try {
            /** @var \project\modules\cruds\models\ActiveRecord|PublicationItem $model */
            $model = $this->findModel($id);
            /** @var ActiveRecord $translation */
            $translation = $model->getPublicationItemTranslations()->andWhere(['language' => \Yii::$app->language])->one();


            $translation->delete();

        } catch (\Exception $e) {
            \Yii::$app->getSession()->addFlash('error', $e->errorInfo[2] ?? $e->getMessage());
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionDeleteBaseModel($id)
    {
        $model = $this->findModel($id);

        if (PublicationHelper::checkModelAccess($model)) {
            try {
                // no null pointer exception because if also checks if model not null
                $model->delete();

            } catch (\Exception $e) {
                \Yii::$app->session->addFlash('error', $e->errorInfo[2] ?? $e->getMessage());
            }
        }
        else {
            \Yii::$app->session->addFlash('warning', Yii::t('bikeadmin', 'You are not allowed to delete the base record'));
        }


        return $this->redirect(\Yii::$app->request->referrer);
    }
}
