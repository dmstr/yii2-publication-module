<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationItem as BasePublicationItem;
use dosamigos\translateable\TranslateableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dmstr_publication_item}}".
 *
 * @property string teaser_widget_json
 * @property string content_widget_json
 */
class PublicationItem extends BasePublicationItem
{

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_item}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['translatable'] = [
            'class' => TranslateableBehavior::className(),
            'relation' => 'publicationItemTranslations',
            'skipSavingDuplicateTranslation' => true,
            'translationAttributes' => [
                'content_widget_json',
                'teaser_widget_json',
                'title'
            ],
            'deleteEvent' => ActiveRecord::EVENT_BEFORE_DELETE,
            'restrictDeletion' => TranslateableBehavior::DELETE_LAST
        ];
        $behaviors['translatable_meta'] = [
            'class' => TranslateableBehavior::className(),
            'relation' => 'publicationItemMetas',
            'fallbackLanguage' => false,
            'skipSavingDuplicateTranslation' => false,
            'translationAttributes' => [
                'status',
                'release_date',
                'end_date'
            ],
            'deleteEvent' => ActiveRecord::EVENT_BEFORE_DELETE
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'crud' => [
                'category_id',
                'content_widget_json',
                'teaser_widget_json',
                'status',
                'title',
                'release_date',
                'end_date'
            ],
            'meta' => [
                'status',
                'release_date',
                'end_date'
            ]
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['release_date', 'title'], 'required'],
                ['end_date', 'safe'],
                [['content_widget_json', 'teaser_widget_json', 'status'], 'string'],
                [['title'], 'string', 'max' => 255],
                [
                    'status',
                    'in',
                    'range' => [
                        PublicationItemMeta::STATUS_DRAFT,
                        PublicationItemMeta::STATUS_PUBLISHED,
                    ]
                ]
            ]
        );
    }


    public $content_widget_schema = [];
    public $teaser_widget_schema = [];

    /**
     * @param $publicationCategoryId
     */
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

    /**
     * @param $publicationCategoryId
     */
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
