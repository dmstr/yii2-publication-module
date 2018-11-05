<?php


namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\controllers\crud\actions\DeleteAttachment;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\search\PublicationItem as PublicationItemSearch;


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
        return $actions;
    }
}
