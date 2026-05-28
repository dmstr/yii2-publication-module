<?php

namespace dmstr\modules\publication;

use dmstr\web\traits\AccessBehaviorTrait;
use Yii;

class Module extends \yii\base\Module
{

    use AccessBehaviorTrait;

    public $defaultRoute = 'crud';

    public $backendLayout = '@backend/views/layouts/box';

    /**
     * If defined, this role grants the user who assigned it access to items with the status "draft"
     *
     * @var null|string
     */
    public $previewItemRole = null;

    /**
     * Render cell widgets in frontend detail view (or not)
     *
     * @var bool
     */
    public $showWidgetCellsInDetailView = true;

    /**
     * RSS channel title. Defaults to Yii::$app->name when null.
     * @var null|string
     */
    public $rssChannelTitle = null;

    /**
     * RSS channel description. Defaults to an empty string when null.
     * @var null|string
     */
    public $rssChannelDescription = null;

    /**
     * RSS channel language (e.g. "en", "de"). Defaults to Yii::$app->language when null.
     * @var null|string
     */
    public $rssChannelLanguage = null;

    /**
     * Callable that returns the RSS item content (HTML allowed) for a PublicationItem.
     * Signature: fn(\dmstr\modules\publication\models\crud\PublicationItem $item): string
     * When null, only the item title is exposed and content stays empty.
     * @var null|callable
     */
    public $rssItemContentCallback = null;

    /**
     * Default item count for the RSS feed when the "limit" query param is omitted.
     * @var int
     */
    public $rssDefaultLimit = 50;

    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        $controller = $action->controller;

        if ($controller->id === 'crud' || strpos($controller::className(), "{$this->id}\\controllers\\crud") !== false) {
            $action->controller->layout = $this->backendLayout;
            Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => Yii::t('publication', 'Publications'), 'url' => ['/'.$this->id]];
        }

        return parent::beforeAction($action);
    }
}
