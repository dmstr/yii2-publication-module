<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\controllers\crud\actions\Create;
use dmstr\modules\publication\controllers\crud\actions\Delete;
use dmstr\modules\publication\controllers\crud\actions\DeleteBaseModel;
use dmstr\modules\publication\controllers\crud\actions\Index;
use dmstr\modules\publication\controllers\crud\actions\Update;
use dmstr\modules\publication\controllers\crud\actions\View;
use project\modules\dealer\models\ActiveRecord;
use yii\web\Controller;

/**
 * Class BaseController
 * @package dmstr\modules\publication\controllers\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property ActiveRecord model
 * @property ActiveRecord searchModel
 */
class BaseController extends Controller
{

    public $model;
    public $searchModel;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['index'] = [
            'class' => Index::class,
            'searchModel' => $this->searchModel
        ];
        $actions['view'] = [
            'class' => View::class,
            'model' => $this->model,
        ];
        $actions['create'] = [
            'class' => Create::class,
            'model' => $this->model,
        ];
        $actions['update'] = [
            'class' => Update::class,
            'model' => $this->model,
        ];
        $actions['delete'] = [
            'class' => Delete::class,
            'model' => $this->model,
        ];
        $actions['delete-base-model'] = [
            'class' => DeleteBaseModel::class,
            'model' => $this->model,
        ];
        return $actions;
    }
}