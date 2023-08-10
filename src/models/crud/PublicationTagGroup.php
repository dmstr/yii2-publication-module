<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\query\PublicationTagGroupQuery;
use dosamigos\translateable\TranslateableBehavior;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * @property string $name
 * @property-read PublicationTag[] $tags
 *
*/
class PublicationTagGroup extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_tag_group}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors ['timestamp'] = TimestampBehavior::class;
        $behaviors['translation'] = [
            'class' => TranslateableBehavior::class,
            'relation' => 'translations',
            'skipSavingDuplicateTranslation' => true,
            'translationAttributes' => [
                'name'
            ],
            'deleteEvent' => ActiveRecord::EVENT_BEFORE_DELETE,
            'restrictDeletion' => TranslateableBehavior::DELETE_LAST
        ];
        return $behaviors;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['crud'] = [
            'name'
        ];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['requiredAttributes'] = ['name', 'required'];
        return $rules;
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PublicationTagGroupTranslation::class, ['tag_group_id' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     * @return ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(PublicationTag::class, ['tag_group_id' => 'id']);
    }

    public function getLabel()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     * @return PublicationTagGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PublicationTagGroupQuery(static::class);
    }
}
