<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\models\crud;


use yii\behaviors\TimestampBehavior;

/**
 * Class PublicationTagTranslation
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
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
        return '{{%dmstr_publication_item}}';
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
        $rules['requiredAttributes'] = ['name', 'required'];
        return $rules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(PublicationTag::class, ['id' => 'tag_id']);
    }
}