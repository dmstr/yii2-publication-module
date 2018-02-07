<?php

namespace dmstr\modules\publication\models\crud;

use Yii;
use \dmstr\modules\publication\models\crud\base\PublicationItemTranslation as BasePublicationItemTranslation;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dmstr_publication_item_translation}}".
 */
class PublicationItemTranslation extends BasePublicationItemTranslation
{

    public function getLanguageCode()
    {
        return Yii::$app->language;
    }

}
