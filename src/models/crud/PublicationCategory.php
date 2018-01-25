<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationCategory as BasePublicationCategory;
use hrzg\widget\models\crud\WidgetTemplate;

/**
 * This is the model class for table "{{%dmstr_publication_category}}".
 */
class PublicationCategory extends BasePublicationCategory
{

    /**
     * @param $properties
     * @param $teaser
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($properties, $teaser)
    {
        $widgetTemplate = $teaser !== true ? $this->contentWidgetTemplate : $this->teaserWidgetTemplate;
        if ($widgetTemplate instanceof WidgetTemplate) {
            return (new \Twig_Environment(new \Twig_Loader_String()))->render($widgetTemplate->twig_template, $properties);
        }
        return null;
    }
}
