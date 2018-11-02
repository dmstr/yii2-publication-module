<?php


namespace dmstr\modules\publication\models\crud\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "app_dmstr_publication_item".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string ref_lang
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \dmstr\modules\publication\models\crud\PublicationCategory $category
 * @property \dmstr\modules\publication\models\crud\PublicationItemMeta[] $publicationItemMetas
 * @property \dmstr\modules\publication\models\crud\PublicationItemTranslation[] $publicationItemTranslations
 * @property string $aliasModel
 */
abstract class PublicationItem extends \dmstr\modules\publication\models\crud\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_dmstr_publication_item';
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\query\PublicationItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\publication\models\crud\query\PublicationItemQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'required'],
            [['category_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\modules\publication\models\crud\PublicationCategory::class, 'targetAttribute' => ['category_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('publication', 'ID'),
            'ref_lang' => Yii::t('publication', 'Ref Lang'),
            'category_id' => Yii::t('publication', 'Category ID'),
            'created_at' => Yii::t('publication', 'Created At'),
            'updated_at' => Yii::t('publication', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(\dmstr\modules\publication\models\crud\PublicationCategory::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetas()
    {
        return $this->hasMany(\dmstr\modules\publication\models\crud\PublicationItemMeta::class, ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(\dmstr\modules\publication\models\crud\PublicationItemTranslation::class, ['item_id' => 'id']);
    }

}
