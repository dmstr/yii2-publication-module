<?php
/**
 * @var dmstr\modules\publication\models\crud\PublicationItem $model
*/

use dmstr\modules\publication\widgets\ModelVersions;

echo ModelVersions::widget([
    'label' => Yii::t('publication', 'Base'),
    'model' => $model,
    'relations' => [
        'category' => [
            'label' => Yii::t('publication', 'Category'),
            'model' => $model->category
        ],
        'translations' => [
            'label' => Yii::t('publication', 'Translations'),
            'model' => $model->translations
        ],
        'metas' => [
            'label' => Yii::t('publication', 'Metas'),
            'model' => $model->metas
        ]
    ]
]);
