<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\query\PublicationTagGroupTranslationQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use Yii;

class PublicationTagGroupTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_tag_group_translation}}';
    }

    /**
     * @inheritdoc
     * @return PublicationTagGroupTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PublicationTagGroupTranslationQuery(static::class);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors ['timestamp'] = TimestampBehavior::class;
        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['uniqueAttributes'] = [['tag_group_id', 'language', 'name'], 'unique', 'targetAttribute' => ['tag_group_id', 'language', 'name']];
        $rules['integerAttributes'] = ['tag_group_id', 'integer'];
        $rules['shortStringAttributes'] = ['language', 'string', 'max' => 7];
        $rules['requiredAttributes'] = ['name', 'required'];
        $rules['tagGroupRef'] = [['tag_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicationTagGroup::class, 'targetAttribute' => ['tag_group_id' => 'id']];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => Yii::t('publication', 'Tag ID'),
            'language' => Yii::t('publication', 'Language'),
            'name' => Yii::t('publication', 'Name')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTagGroup()
    {
        return $this->hasOne(PublicationTagGroup::class, ['id' => 'tag_group_id']);
    }
}
