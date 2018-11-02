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
use yii\helpers\Url;

/**
 * Class Index
 * @package dmstr\modules\publication\controllers\crud\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property ActiveRecord searchModel
 */
class Index extends Action
{
    public $searchModel;

    /**
     * @return mixed
     */
    public function run()
    {
        Url::remember();
        $searchModel = new $this->searchModel;

        return $this->controller->render($this->id, [
            'dataProvider' => $searchModel->search(\Yii::$app->request->get()),
            'searchModel' => $searchModel,
        ]);
    }
}