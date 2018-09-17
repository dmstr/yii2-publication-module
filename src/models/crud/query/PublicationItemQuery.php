<?php

namespace dmstr\modules\publication\models\crud\query;

use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemMeta;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItem]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItem
 */
class PublicationItemQuery extends \yii\db\ActiveQuery
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function published()
    {
        $this->leftJoin(PublicationItemMeta::tableName(),'item_id=' . PublicationItem::tableName() .'.id');
        $todaysDate = date('Y-m-d');
        $this->andWhere('release_date <= :todaysDate' , [':todaysDate' => $todaysDate]);
        $this->andWhere('status = "' . \dmstr\modules\publication\models\crud\PublicationItem::STATUS_PUBLISHED. '"');
        $this->andWhere('end_date >= :todaysDate OR end_date IS NULL' , [':todaysDate' => $todaysDate]);
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
