<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\actions;


use dmstr\modules\publication\components\PublicationHelper;
use dmstr\modules\publication\models\crud\ActiveRecord;
use Yii;

/**
 * Class DeleteBaseModel
 * @package dmstr\modules\publication\controllers\crud\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class DeleteBaseModel extends BaseAction
{
    /**
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if (PublicationHelper::checkModelAccess($model)) {
            $transaction = ActiveRecord::getDb()->beginTransaction();
            try {
                // no null pointer exception because if also checks if model not null
                $model->delete();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->addFlash('error', $e->errorInfo[2] ?? $e->getMessage());
            }
        } else {
            \Yii::$app->session->addFlash('warning', Yii::t('publication', 'You are not allowed to delete the base record'));
        }


        return $this->controller->redirect(\Yii::$app->request->referrer);
    }
}