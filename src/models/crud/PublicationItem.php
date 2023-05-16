<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationItem as BasePublicationItem;
use dosamigos\translateable\TranslateableBehavior;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\HttpException;

/**
 * This is the model class for table "{{%dmstr_publication_item}}".
 *
 * @property string teaser_widget_json
 * @property string content_widget_json
 * @property PublicationTag[] tags
 * @property int $contentSchemaByCategoryId
 * @property int $teaserSchemaByCategoryId
 * @property-read mixed $label
 * @property array tagIds
 */
class PublicationItem extends BasePublicationItem
{

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
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
                'end_date',
                'item_start_date',
                'item_end_date'
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
        $rules['safeAttributes'] = [['end_date', 'tagIds','item_start_date', 'item_end_date'], 'safe'];
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
     * We must get/set release_date and (optional) end_date from translation_meta from ref_lang.
     * Otherwise we would destroy this information from "fallback language" because translation_meta
     * does not have fallback from translatable behaviour!
     */
    public function afterFind()
    {
        parent::afterFind();
        if (! $this->release_date) {
            $fallback_meta = $this->getFallbackMeta();
            $this->release_date = $fallback_meta ? $fallback_meta->release_date : date('Y-m-d H:i:00');
        }
        if (! $this->end_date) {
            $fallback_meta = $this->getFallbackMeta();
            $this->end_date = $fallback_meta ? $fallback_meta->end_date : null;
        }

    }

    /**
     * @return PublicationItem|null
     */
    public function getFallbackMeta()
    {
        return $this->getMetas()->andWhere(['language' => strtolower($this->ref_lang)])->one();
    }

    /**
     * @throws InvalidConfigException
     * @return ActiveQuery
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

                    $junction = new PublicationItemXTag(['item_id' => $this->id, 'tag_id' => $tagId]);

                    if (!$junction->save()) {
                        Yii::error('Error while saving publication: ' . print_r($junction->errors, 1), __CLASS__);
                        throw new HttpException(500, Yii::t('publication', 'Error while saving publication'));
                    }
                }
            }
            if ($transaction && $transaction->isActive) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            if ($transaction && $transaction->isActive) {
                $transaction->rollBack();
            }
            Yii::error($e->getMessage(),__METHOD__);
        }
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['tagIds'] = Yii::t('publication', 'Tags');
        return $attributeLabels;
    }

    public function attributeHints()
    {
        $attributeHints = parent::attributeHints();
        $attributeHints['release_date'] = Yii::t('publication', 'Selected UTC end date time corresponds to CEST {offset} hours', ['offset' => Html::tag('span', null, ['id' => 'release-date-mez-offset'])]);
        $attributeHints['end_date'] = Yii::t('publication', 'Selected UTC end date time corresponds to CEST {offset} hours', ['offset' => Html::tag('span', null, ['id' => 'end-date-mez-offset'])]);
        return $attributeHints;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert && !$this->load(Yii::$app->request->post())) {
            Yii::error('cannot add tags to publication item',__METHOD__);
        }
    }

    public function getLabel()
    {
        return $this->title;
    }

}
