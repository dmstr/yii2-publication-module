<?php

namespace dmstr\modules\publication;


class Module extends \yii\base\Module
{

    public $widgetModuleId = 'widgets';
    public $defaultRoute = 'category';

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
