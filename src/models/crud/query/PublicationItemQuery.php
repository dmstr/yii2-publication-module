<?php

namespace dmstr\modules\publication\models\crud\query;

use dmstr\modules\publication\models\crud\PublicationItem;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItem]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItem
 */
class PublicationItemQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => PublicationItem::STATUS_PUBLISHED]);
        $todaysDate = date('Y-m-d');
        $this->andWhere('release_date <= :todaysDate' , [':todaysDate' => $todaysDate]);
        $this->andWhere('end_date >= :todaysDate' , [':todaysDate' => $todaysDate]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\PublicationItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\publication\models\crud\PublicationItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
