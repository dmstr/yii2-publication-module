<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\api;


use yii\filters\AccessControl;
use yii\rest\ActiveController;

/**
 * Class BaseController
 * @package dmstr\modules\publication\controllers\crud\api
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class BaseController extends ActiveController
{
    /**
     *
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        return \Yii::$app->user->can($this->module->id . '_' . $this->id . '_' . $action->id, ['route' => true]);
                    },
                ]
            ]
        ];
        return $behaviors;

    }
}