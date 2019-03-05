<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\actions;


use project\modules\dealer\models\ActiveRecord;

/**
 * Class Delete
 * @package dmstr\modules\publication\controllers\crud\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class Delete extends BaseAction
{
    /**
     * @param $id
     * @return string
     * @throws \Throwable
     */
    public function run($id)
    {
        $transaction = \dmstr\modules\publication\models\crud\ActiveRecord::getDb()->beginTransaction();
        try {
            $model = $this->findModel($id);
            /** @var ActiveRecord $translation */
            $translation = $model->getTranslations()->andWhere(['language' =>  strtolower(\Yii::$app->language)])->one();

            if ($translation) {
                $translation->delete();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->getSession()->addFlash('error', $e->errorInfo[2] ?? $e->getMessage());
        }

        $transaction->commit();
        return $this->controller->redirect(\Yii::$app->request->referrer);
    }
}