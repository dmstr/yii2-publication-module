<?php


namespace dmstr\modules\publication\models\crud\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "app_dmstr_publication_category_translation".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $language
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \dmstr\modules\publication\models\crud\PublicationCategory $category
 * @property string $aliasModel
 */
abstract class PublicationCategoryTranslation extends \dmstr\modules\publication\models\crud\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_dmstr_publication_category_translation';
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\query\PublicationCategoryTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\publication\models\crud\query\PublicationCategoryTranslationQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'language'], 'required'],
            [['category_id'], 'integer'],
            [['language'], 'string', 'max' => 7],
            [['title'], 'string', 'max' => 255],
            [['category_id', 'language'], 'unique', 'targetAttribute' => ['category_id', 'language']],
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
            'category_id' => Yii::t('publication', 'Category ID'),
            'language' => Yii::t('publication', 'Language'),
            'title' => Yii::t('publication', 'Title'),
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


}
