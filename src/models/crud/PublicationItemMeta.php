<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationItemMeta as BasePublicationItemMeta;

/**
 * This is the model class for table "app_dmstr_publication_item_meta".
 */
class PublicationItemMeta extends BasePublicationItemMeta
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_item_meta}}';
    }
}
