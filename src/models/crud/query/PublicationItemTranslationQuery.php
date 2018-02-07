<?php

namespace dmstr\modules\publication\models\crud\query;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\publication\models\crud\PublicationItemTranslation]].
 *
 * @see \dmstr\modules\publication\models\crud\PublicationItemTranslation
 */
class PublicationItemTranslationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

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
