<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationItem as BasePublicationItem;
use dosamigos\translateable\TranslateableBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * This is the model class for table "{{%dmstr_publication_item}}".
 *
 * @property string teaser_widget_json
 * @property string content_widget_json
 * @property PublicationTag[] tags
 * @property int $contentSchemaByCategoryId
 * @property int $teaserSchemaByCategoryId
 * @property array tagIds
 */
class PublicationItem extends BasePublicationItem
{

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    public $content_widget_schema = [];
    public $teaser_widget_schema = [];

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
            'class' => TranslateableBehavior::class,
            'relation' => 'translations',
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
            'class' => TranslateableBehavior::class,
            'relation' => 'metas',
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
        $scenarios = parent::scenarios();
        $scenarios['crud'] = [
            'category_id',
            'content_widget_json',
            'teaser_widget_json',
            'status',
            'title',
            'release_date',
            'end_date'
        ];
        $scenarios['meta'] = [
            'status',
            'release_date',
            'end_date'
        ];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['requiredAttributes'] = [['release_date', 'title', 'ref_lang'], 'required'];
        $rules['safeAttributes'] = [['end_date', 'tagIds'], 'safe'];
        $rules['stringAttributes'] = [['content_widget_json', 'teaser_widget_json', 'status'], 'string'];
        $rules['stringLengthAttributes'] = ['title', 'string', 'max' => 255];
        $rules['inRangeAttributes'] = [
            'status',
            'in',
            'range' => [
                PublicationItemMeta::STATUS_DRAFT,
                PublicationItemMeta::STATUS_PUBLISHED,
            ]
        ];
        return $rules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(PublicationTag::class, ['id' => 'tag_id'])->viaTable('{{%dmstr_publication_tag_x_item}}', ['item_id' => 'id']);
    }

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

    public function getTagIds()
    {
        return ArrayHelper::map($this->tags, 'id', 'id');
    }

    public function setTagIds($ids = [])
    {
        $transaction = self::getDb()->beginTransaction();
        try {
            PublicationItemXTag::deleteAll(['item_id' => $this->id]);

            if (is_array($ids)) {
                foreach ($ids as $tagId) {

                    /** @var PublicationItemXTag $junction */
                    $junction = new PublicationItemXTag(['item_id' => $this->id, 'tag_id' => $tagId]);

                    if (!$junction->save()) {
                        Yii::error('Error while saving publication: ' . print_r($junction->errors, 1), __CLASS__);
                        throw new HttpException(500, Yii::t('publication', 'Error while saving publication'));
                    }
                }
            }
            $transaction->commit();
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
        }
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['tagIds'] = Yii::t('publication', 'Tags');
        return $attributeLabels;
    }

}
