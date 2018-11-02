<?php

namespace dmstr\modules\publication\models\crud\query;

use dmstr\modules\publication\models\crud\PublicationItemMeta;
use dmstr\modules\publication\models\crud\PublicationItemTranslation;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItemTranslation]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItemTranslation
 */
class PublicationItemTranslationQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        $this->leftJoin(PublicationItemMeta::tableName(), PublicationItemMeta::tableName() . '.item_id=' . PublicationItemTranslation::tableName() . '.item_id');
        $this->andWhere(['status' => PublicationItemMeta::STATUS_PUBLISHED]);
        return $this;
    }
}
