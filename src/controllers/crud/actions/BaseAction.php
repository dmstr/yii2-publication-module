<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\actions;


use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Class BaseAction
 * @package project\modules\bikeadmin\controllers\batch\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property ActiveRecord model
 */
class BaseAction extends Action
{
    public $model;

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws HttpException
     */
    protected function findModel($id)
    {
        if (($model = $this->model::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}