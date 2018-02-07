<?php

namespace dmstr\modules\publication\models\crud\query;

use dmstr\modules\publication\models\crud\base\PublicationItemTranslation;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItemTranslation]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItemTranslation
 */
class PublicationItemTranslationQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => PublicationItemTranslation::STATUS_PUBLISHED]);
        $todaysDate = date('Y-m-d');
        $this->andWhere('release_date <= :todaysDate' , [':todaysDate' => $todaysDate]);
        $this->andWhere('end_date >= :todaysDate OR end_date IS NULL' , [':todaysDate' => $todaysDate]);
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
