<?php

namespace dmstr\modules\publication\models\crud;

use Yii;
use \dmstr\modules\publication\models\crud\base\PublicationItemMeta as BasePublicationItemMeta;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

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
