<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\models\crud;


use dosamigos\translateable\TranslateableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * Class PublicationTag
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string name
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

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['requiredAttributes'] = ['name', 'required'];
        return $rules;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(PublicationTagTranslation::class, ['tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(PublicationItem::class, ['id' => 'item_id'])->viaTable('{{%dmstr_publication_tag_item}}', ['item_id' => 'id']);
    }
}