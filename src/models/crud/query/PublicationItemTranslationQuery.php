<?php

namespace dmstr\modules\publication\models\crud\query;

use dmstr\modules\publication\models\crud\base\PublicationItemTranslation;
use dmstr\modules\publication\models\crud\PublicationItemMeta;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItemTranslation]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItemTranslation
 */
class PublicationItemTranslationQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        $this->leftJoin(PublicationItemMeta::tableName(),PublicationItemMeta::tableName() . '.item_id=' . PublicationItemTranslation::tableName() .'.item_id');
        $this->andWhere(['status' => PublicationItemMeta::STATUS_PUBLISHED]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\PublicationItemTranslation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\PublicationItemTranslation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
