<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\models\crud;


use dmstr\modules\publication\models\crud\query\PublicationTagQuery;
use dosamigos\translateable\TranslateableBehavior;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use Yii;

/**
 * Class PublicationTag
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string name
 * @property string ref_lang
 * @property string label
 * @property PublicationTagTranslation[] translations
 * @property PublicationItem[] items
 */
class PublicationTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_tag}}';
    }

    /**
     * @inheritdoc
     * @return PublicationTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PublicationTagQuery(static::class);
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
        $rules['requiredAttributes'] = [['name', 'tag_group_id'], 'required'];
        $rules['tagGroupRef'] = [['tag_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicationTagGroup::class, 'targetAttribute' => ['tag_group_id' => 'id']];
        return $rules;
    }

    /**
     * @return ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PublicationTagTranslation::class, ['tag_id' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     * @return ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(PublicationItem::class, ['id' => 'item_id'])->viaTable('{{%dmstr_publication_tag_x_item}}', ['tag_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTagGroup()
    {
        return $this->hasOne(PublicationTagGroup::class, ['id' => 'tag_group_id']);
    }

    public function getLabel()
    {
        return $this->name;
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['name'] = Yii::t('publication', 'Name');
        $attributeLabels['tag_group_id'] = \Yii::t('publication', 'Tag Group ID');
        $attributeLabels['ref_lang'] = \Yii::t('publication', 'Ref Lang');
        return $attributeLabels;
    }
}
