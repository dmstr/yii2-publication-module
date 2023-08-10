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
use yii\db\ActiveQuery;

/**
 * Class PublicationTagGroupXTag
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property PublicationTagGroup tagGroup
 * @property PublicationTag tag
 */
class PublicationTagGroupXTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_tag_group_x_tag}}';
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
        $rules['requiredAttributes'] = [['tag_group_id', 'tag_id'], 'required'];
        $rules['uniqueAttributes'] = [['tag_group_id', 'tag_id'], 'unique', 'targetAttribute' => ['tag_group_id', 'tag_id']];
        $rules['itemExists'] = [['tag_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicationTagGroup::class, 'targetAttribute' => ['tag_group_id' => 'id']];
        $rules['tag_id'] = [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicationTag::class, 'targetAttribute' => ['tag_id' => 'id']];
        return $rules;
    }

    /**
     * @return ActiveQuery
     */
    public function getTagGroup()
    {
        return $this->hasOne(PublicationTagGroup::class, ['id' => 'tag_group_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(PublicationTag::class, ['id' => 'tag_id']);
    }
}
