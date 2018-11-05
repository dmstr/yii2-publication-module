<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\controllers\crud\actions;


use dmstr\modules\publication\models\crud\ActiveRecord;
use dmstr\modules\publication\models\crud\PublicationItem;
use dmstr\modules\publication\models\crud\PublicationItemXTag;
use Yii;

/**
 * Class DeleteAttachment
 * @package dmstr\modules\publication\controllers\crud\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class DeleteAttachment extends BaseAction
{
    /**
     * @param $tagId
     * @param $itemId
     * @return string
     * @throws \Throwable
     */
    public function run($tagId, $itemId)
    {
        /** @var PublicationItem $model */
        $model = PublicationItem::findOne($itemId);

        if ($model !== null && $model->ref_lang === Yii::$app->language) {
            $transaction = ActiveRecord::getDb()->beginTransaction();
            try {
                // no null pointer exception because if also checks if model not null
                $attachment = PublicationItemXTag::findOne(['tag_id' => $tagId, 'item_id' => $itemId]);
                if ($attachment !== null) {
                    $attachment->delete();
                    $transaction->commit();
                } else {
                    \Yii::$app->session->addFlash('warning', Yii::t('bikeadmin', 'Relation between item and tag bet does not exist'));
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->addFlash('error', $e->errorInfo[2] ?? $e->getMessage());
            }
        } else {
            \Yii::$app->session->addFlash('warning', Yii::t('bikeadmin', 'You are not allowed to remove the attached item or tag'));
        }


        return $this->controller->redirect(\Yii::$app->request->referrer);
    }
}