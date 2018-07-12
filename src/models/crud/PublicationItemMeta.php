<?php

namespace dmstr\modules\publication\models\crud;

use Yii;
use \dmstr\modules\publication\models\crud\base\PublicationItemMeta as BasePublicationItemMeta;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_dmstr_publication_item_meta".
 */
class PublicationItemMeta extends BasePublicationItemMeta
{

    public function getLanguageCode()
    {
        return Yii::$app->language;
    }
}
