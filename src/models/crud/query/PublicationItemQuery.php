<?php

namespace dmstr\modules\publication\models\crud\query;

use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemMeta;
use Yii;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItem]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItem
 */
class PublicationItemQuery extends \yii\db\ActiveQuery
{

    public function published()
    {
        $this->leftJoin(PublicationItemMeta::tableName(), 'item_id=' . PublicationItem::tableName() . '.id AND ' . PublicationItemMeta::tableName() . '.language = "' . Yii::$app->language . '"');
        $todaysDate = date('Y-m-d');
        $this->andWhere('release_date <= :todaysDate', [':todaysDate' => $todaysDate]);
        $this->andWhere('status = "' . PublicationItem::STATUS_PUBLISHED . '"');
        $this->andWhere('end_date >= :todaysDate OR end_date IS NULL', [':todaysDate' => $todaysDate]);
        $this->andWhere(['OR',['ref_lang' => Yii::$app->language],['ref_lang' => \Yii::$app->params['fallbackLanguages'][\Yii::$app->language] ?? 'en']]);
        return $this;
    }
}
