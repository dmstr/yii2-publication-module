<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers;

use project\modules\crud\controllers\actions\SchemaAction;
use project\modules\crud\controllers\actions\SettingAction;
use project\modules\crud\models\Accessory;
use project\modules\crud\models\Bike;
use yii\web\Controller;


/**
 * Class JsonSchemaController
 * @package project\modules\crud\controllers
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class JsonSchemaController extends Controller
{

    /**
     * @return array List of actions
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['bikes'] = [
            'class' => SchemaAction::class,
            'modelClass' => Bike::class
        ];
        $actions['accessories'] = [
            'class' => SchemaAction::class,
            'modelClass' => Accessory::class
        ];
        $actions['categories'] = [
            'class' => SettingAction::class,
            'key' => 'categories'
        ];
        $actions['labels'] = [
            'class' => SettingAction::class,
            'key' => 'labels'
        ];
        $actions['img-special-position'] = [
            'class' => SettingAction::class,
            'key' => 'img-special-position'
        ];
        return $actions;
    }
}