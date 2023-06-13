<?php


namespace dmstr\modules\publication\models\crud\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "app_dmstr_publication_item_meta".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $language
 * @property string $status
 * @property string $release_date
 * @property string $end_date
 * @property string $item_start_date
 * @property string $item_end_date
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \dmstr\modules\publication\models\crud\PublicationItem $item
 * @property string $aliasModel
 */
abstract class PublicationItemMeta extends \dmstr\modules\publication\models\crud\ActiveRecord
{


    /**
     * ENUM field values
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    var $enum_labels = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_dmstr_publication_item_meta';
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\query\PublicationItemMetaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\publication\models\crud\query\PublicationItemMetaQuery(get_called_class());
    }

    /**
     * get column status enum value label
     * @param string $value
     * @return string
     */
    public static function getStatusValueLabel($value)
    {
        $labels = self::optsStatus();
        if (isset($labels[$value])) {
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column status ENUM value labels
     * @return array
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_DRAFT => Yii::t('publication', self::STATUS_DRAFT),
            self::STATUS_PUBLISHED => Yii::t('publication', self::STATUS_PUBLISHED),
        ];
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
            [['item_id', 'language', 'release_date'], 'required'],
            [['item_id'], 'integer'],
            [['status'], 'string'],
            [['release_date', 'end_date','item_start_date', 'item_end_date'], 'safe'],
            [['language'], 'string', 'max' => 7],
            [['item_id', 'language'], 'unique', 'targetAttribute' => ['item_id', 'language']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\modules\publication\models\crud\PublicationItem::class, 'targetAttribute' => ['item_id' => 'id']],
            ['status', 'in', 'range' => [
                self::STATUS_DRAFT,
                self::STATUS_PUBLISHED,
            ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('publication', 'ID'),
            'item_id' => Yii::t('publication', 'Item ID'),
            'language' => Yii::t('publication', 'Language'),
            'status' => Yii::t('publication', 'Status'),
            'release_date' => Yii::t('publication', 'Release Date'),
            'end_date' => Yii::t('publication', 'End Date'),
            'item_start_date' => Yii::t('publication', 'Item Start Date'),
            'item_end_date' => Yii::t('publication', 'Item End Date'),
            'created_at' => Yii::t('publication', 'Created At'),
            'updated_at' => Yii::t('publication', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(\dmstr\modules\publication\models\crud\PublicationItem::class, ['id' => 'item_id']);
    }

}
