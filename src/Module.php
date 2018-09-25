<?php

namespace dmstr\modules\publication;

use dmstr\web\traits\AccessBehaviorTrait;

class Module extends \yii\base\Module
{

    use AccessBehaviorTrait;

    public $widgetModuleId = 'widgets';
    public $defaultRoute = 'crud';

    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        $controller = $action->controller;

        if ($controller->id === 'crud' || strpos($controller::className(), "{$this->id}\\controllers\\crud") !== false) {
            $action->controller->layout = '@backend/views/layouts/box';
        }

        return parent::beforeAction($action);
    }
}
