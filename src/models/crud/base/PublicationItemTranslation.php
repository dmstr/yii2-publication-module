<?php


namespace dmstr\modules\publication\models\crud\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "app_dmstr_publication_item_translation".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $language
 * @property string $title
 * @property string $content_widget_json
 * @property string $teaser_widget_json
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \dmstr\modules\publication\models\crud\PublicationItem $item
 * @property string $aliasModel
 */
abstract class PublicationItemTranslation extends \dmstr\modules\publication\models\crud\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_dmstr_publication_item_translation';
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\query\PublicationItemTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\publication\models\crud\query\PublicationItemTranslationQuery(get_called_class());
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
            [['item_id', 'language', 'title'], 'required'],
            [['item_id'], 'integer'],
            [['content_widget_json', 'teaser_widget_json'], 'string'],
            [['language'], 'string', 'max' => 7],
            [['title'], 'string', 'max' => 255],
            [['item_id', 'language'], 'unique', 'targetAttribute' => ['item_id', 'language']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\modules\publication\models\crud\PublicationItem::class, 'targetAttribute' => ['item_id' => 'id']]
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
            'title' => Yii::t('publication', 'Title'),
            'content_widget_json' => Yii::t('publication', 'Content Widget Json'),
            'teaser_widget_json' => Yii::t('publication', 'Teaser Widget Json'),
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
