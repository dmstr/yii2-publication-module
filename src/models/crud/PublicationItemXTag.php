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
 * Class PublicationItemXTag
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property PublicationItem item
 * @property PublicationTag tag
 */
class PublicationItemXTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_tag_x_item}}';
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
        $rules['requiredAttributes'] = [['item_id', 'tag_id'], 'required'];
        $rules['uniqueAttributes'] = [['item_id', 'tag_id'], 'unique', 'targetAttribute' => ['item_id', 'tag_id']];
        $rules['itemExists'] = [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicationItem::class, 'targetAttribute' => ['item_id' => 'id']];
        $rules['tag_id'] = [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => PublicationTag::class, 'targetAttribute' => ['tag_id' => 'id']];
        return $rules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessory()
    {
        return $this->hasOne(PublicationItem::class, ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(PublicationTag::class, ['id' => 'tag_id']);
    }
}