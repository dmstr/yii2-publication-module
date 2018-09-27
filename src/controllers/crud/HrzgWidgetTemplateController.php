<?php
/**
 * /app/src/../runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace dmstr\modules\publication\controllers\crud;

/**
 * This is the class for controller "HrzgWidgetTemplateController".
 */
class HrzgWidgetTemplateController extends \hrzg\widget\controllers\crud\WidgetTemplateController
{
    public function init()
    {
        parent::init();
        $this->viewPath = '@hrzg/widget/views/crud/widget-template';
    }

}
