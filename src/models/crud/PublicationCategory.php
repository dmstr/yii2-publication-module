<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationCategory as BasePublicationCategory;
use hrzg\widget\models\crud\WidgetTemplate;
use dosamigos\translateable\TranslateableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dmstr_publication_category}}".
 */
class PublicationCategory extends BasePublicationCategory
{

    public function getLabel()
    {
        return $this->title;
    }

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
            return (new \Twig_Environment(new \Twig_Loader_String(),['autoescape' => false]))->render($widgetTemplate->twig_template, $properties);
        }
        return null;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'translatable' => [
                'class' => TranslateableBehavior::className(),
                'relation' => 'publicationCategoryTranslations',
                'languageField' => 'language_code',
                'translationAttributes' => [
                    'title',
                ],
            ],
        ]);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'crud' => [
                'title',
                'content_widget_template_id',
                'teaser_widget_template_id'
            ]
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['title'], 'string', 'max' => 80],
            ]
        );
    }
}
