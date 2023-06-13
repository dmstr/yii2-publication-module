<?php


namespace dmstr\modules\publication\models\crud\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "app_dmstr_publication_category".
 *
 * @property integer $id
 * @property string $ref_lang
 * @property integer $content_widget_template_id
 * @property integer $teaser_widget_template_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \dmstr\modules\publication\models\crud\HrzgWidgetTemplate $contentWidgetTemplate
 * @property \dmstr\modules\publication\models\crud\HrzgWidgetTemplate $teaserWidgetTemplate
 * @property \dmstr\modules\publication\models\crud\PublicationCategoryTranslation[] $publicationCategoryTranslations
 * @property \dmstr\modules\publication\models\crud\PublicationItem[] $publicationItems
 * @property string $aliasModel
 */
abstract class PublicationCategory extends \dmstr\modules\publication\models\crud\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_dmstr_publication_category';
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\query\PublicationCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\publication\models\crud\query\PublicationCategoryQuery(get_called_class());
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
            [['content_widget_template_id', 'teaser_widget_template_id'], 'required'],
            [['content_widget_template_id', 'teaser_widget_template_id'], 'integer'],
            [['content_widget_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\modules\publication\models\crud\HrzgWidgetTemplate::class, 'targetAttribute' => ['content_widget_template_id' => 'id']],
            [['teaser_widget_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => \dmstr\modules\publication\models\crud\HrzgWidgetTemplate::class, 'targetAttribute' => ['teaser_widget_template_id' => 'id']]
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
            'content_widget_template_id' => Yii::t('publication', 'Content Widget Template ID'),
            'teaser_widget_template_id' => Yii::t('publication', 'Teaser Widget Template ID'),
            'created_at' => Yii::t('publication', 'Created At'),
            'updated_at' => Yii::t('publication', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentWidgetTemplate()
    {
        return $this->hasOne(\dmstr\modules\publication\models\crud\HrzgWidgetTemplate::class, ['id' => 'content_widget_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeaserWidgetTemplate()
    {
        return $this->hasOne(\dmstr\modules\publication\models\crud\HrzgWidgetTemplate::class, ['id' => 'teaser_widget_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(\dmstr\modules\publication\models\crud\PublicationCategoryTranslation::class, ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(\dmstr\modules\publication\models\crud\PublicationItem::class, ['category_id' => 'id']);
    }

}
