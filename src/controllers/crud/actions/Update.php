<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\actions;


use yii\helpers\Url;

/**
 * Class Update
 * @package dmstr\modules\publication\controllers\crud\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class Update extends BaseAction
{
    /**
     * @param $id
     * @return string
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->controller->redirect(Url::previous());
        }
        return $this->controller->render($this->id, [
            'model' => $model,
        ]);
    }
}