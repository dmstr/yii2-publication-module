<?php

namespace dmstr\modules\publication\models\crud;

use Yii;
use \dmstr\modules\publication\models\crud\base\PublicationCategoryTranslation as BasePublicationCategoryTranslation;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dmstr_publication_category_translation}}".
 */
class PublicationCategoryTranslation extends BasePublicationCategoryTranslation
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dmstr_publication_category_translation}}';
    }
}
