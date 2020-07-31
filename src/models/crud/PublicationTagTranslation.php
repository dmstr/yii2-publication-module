<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\models\crud;


use dmstr\modules\publication\models\crud\query\PublicationTagTranslationQuery;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class PublicationTagTranslation
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int tag_id
 * @property string language
 * @property string name
 * @property PublicationTag tag
 */
class PublicationTagTranslation extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_tag_translation}}';
    }

    /**
     * @inheritdoc
     * @return PublicationTagTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PublicationTagTranslationQuery(static::class);
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
        $rules['uniqueAttributes'] = [['tag_id', 'language', 'name'], 'unique', 'targetAttribute' => ['tag_id', 'language', 'name']];
        $rules['integerAttributes'] = ['tag_id', 'integer'];
        $rules['shortStringAttributes'] = ['language', 'string', 'max' => 7];
        $rules['requiredAttributes'] = ['name', 'required'];
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
    public function getTag()
    {
        return $this->hasOne(PublicationTag::class, ['id' => 'tag_id']);
    }

}