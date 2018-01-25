<?php

namespace dmstr\modules\publication\models\crud;

use Yii;
use \dmstr\modules\publication\models\crud\base\PublicationCategory as BasePublicationCategory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dmstr_publication_category}}".
 */
class PublicationCategory extends BasePublicationCategory
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
