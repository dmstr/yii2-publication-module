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
use dmstr\modules\publication\models\crud\search\PublicationItem as PublicationItemSearch;
use dmstr\bootstrap\Tabs;


/**
 * This is the class for controller "PublicationItemController".
 */
class PublicationItemController extends \dmstr\modules\publication\controllers\crud\base\PublicationItemController
{

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
    public function actionIndex() {
        $searchModel  = new PublicationItemSearch;
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
