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
