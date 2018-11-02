<?php

namespace dmstr\modules\publication\models\crud;

use Yii;
use \dmstr\modules\publication\models\crud\base\PublicationItemTranslation as BasePublicationItemTranslation;

/**
 * This is the model class for table "{{%dmstr_publication_item_translation}}".
 */
class PublicationItemTranslation extends BasePublicationItemTranslation
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_item_translation}}';
    }

}
