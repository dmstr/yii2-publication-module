<?php
/**
 * /app/src/../runtime/giiant/f197ab8e55d1e29a2dea883e84983544
 *
 * @package default
 */


namespace dmstr\modules\publication\controllers\crud\api;

/**
 * This is the class for REST controller "PublicationCategoryController".
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class PublicationCategoryController extends \yii\rest\ActiveController
{
    public $modelClass = 'dmstr\modules\publication\models\crud\PublicationCategory';

    /**
     *
     * @inheritdoc
     * @return unknown
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->can($this->module->id . '_' . $this->id . '_' . $action->id, ['route' => true]);
                            },
                        ]
                    ]
                ]
            ]
        );
    }


}
