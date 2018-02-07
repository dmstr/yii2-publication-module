<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationItem as BasePublicationItem;
use yii\helpers\ArrayHelper;
use JsonSchema\Validator;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%dmstr_publication_item}}".
 */
class PublicationItem extends BasePublicationItem
{

    public $content_widget_schema = [];
    public $teaser_widget_schema = [];

    public function setContentSchemaByCategoryId($publicationCategoryId)
    {
        $publicationCategory = PublicationCategory::findOne($publicationCategoryId);

        if ($publicationCategory instanceof PublicationCategory) {

            /** @var HrzgWidgetTemplate $contentWidgetTemplate */
            $contentWidgetTemplate = $publicationCategory->getContentWidgetTemplate()->one();
            if ($contentWidgetTemplate instanceof HrzgWidgetTemplate) {
                $this->content_widget_schema = json_decode($contentWidgetTemplate->json_schema, 1);
            }
        }


    }

    public function setTeaserSchemaByCategoryId($publicationCategoryId)
    {
        $publicationCategory = PublicationCategory::findOne($publicationCategoryId);

        if ($publicationCategory instanceof PublicationCategory) {

            /** @var HrzgWidgetTemplate $teaserWidgetTemplate */
            $teaserWidgetTemplate = $publicationCategory->getTeaserWidgetTemplate()->one();
            if ($teaserWidgetTemplate instanceof HrzgWidgetTemplate) {
                $this->teaser_widget_schema = json_decode($teaserWidgetTemplate->json_schema, 1);
            }
        }
    }

//    public function rules()
//    {
//        return ArrayHelper::merge(parent::rules(), [
//            [
//                ['teaser_widget_json','content_widget_json'],
//                function ($attribute) {
//                    $validator = new Validator();
//                    $type = $attribute === 'teaser_widget_json' ? 'teaserWidgetTemplate' : 'contentWidgetTemplate';
//                    $obj = Json::decode($this->publicationCategory->$type->json_schema, false);
//                    $data = Json::decode($this->{$attribute}, false);
//                    $validator->check($data, $obj);
//                    if ($validator->getErrors()) {
//                        foreach ($validator->getErrors() as $error) {
//                            $this->addError($error['property'], "{$error['property']}: {$error['message']}");
//                        }
//                    }
//                },
//            ],
//        ]);
//    }
}
