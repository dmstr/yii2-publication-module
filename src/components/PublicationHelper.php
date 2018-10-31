<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\components;


use dmstr\modules\publication\models\crud\ActiveRecord;
use dmstr\modules\publication\models\crud\PublicationItem;
use dosamigos\translateable\TranslateableBehavior;
use Yii;

/**
 * Class PublicationHelper
 * @package dmstr\modules\publication\components
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class PublicationHelper
{

    /**
     * @param ActiveRecord|PublicationItem $model
     * @return bool
     */
    public static function checkModelAccess($model)
    {
        $ret = false;
        if ($model !== null && $model->access_domain === Yii::$app->language) {
            foreach ($model->behaviors as $behavior) {
                if ($behavior instanceof TranslateableBehavior && $behavior->restrictDeletion === TranslateableBehavior::DELETE_LAST && $behavior->deleteEvent === \yii\db\ActiveRecord::EVENT_BEFORE_DELETE) {
                    /** @var TranslateableBehavior $behavior */
                    $relation = $behavior->relation;
                    if (\count($model->$relation) < 2) {
                        $ret = true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return $ret;
    }
}