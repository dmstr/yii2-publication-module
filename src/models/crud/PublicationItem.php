<?php

namespace dmstr\modules\publication\models\crud;

use dmstr\modules\publication\models\crud\base\PublicationItem as BasePublicationItem;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%dmstr_publication_item}}".
 */
class PublicationItem extends BasePublicationItem
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            'translatable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'name',
                    'created_at',
                    'updated_at'
                ],
            ],
        ]);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), ['crud' => ['publication_category_id','content_widget_json','teaser_widget_json','status','title']]);
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['publication_category_id'], 'required'],
                [['publication_category_id'], 'integer'],
                [['content_widget_json', 'teaser_widget_json', 'status'], 'string'],
                [
                    ['publication_category_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => \dmstr\modules\publication\models\crud\PublicationCategoryTranslation::className(),
                    'targetAttribute' => ['publication_category_id' => 'id']
                ],
                [['title'], 'string', 'max' => 80],
                [
                    'status',
                    'in',
                    'range' => [
                        PublicationItemTranslation::STATUS_DRAFT,
                        PublicationItemTranslation::STATUS_PUBLISHED,
                    ]
                ]
            ]
        );
    }

}
