<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\models\crud;


/**
 * Class ActiveRecord
 *
 * @package dmstr\modules\publication\models\crud
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
    */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (class_exists('bedezign\yii2\audit\AuditTrailBehavior')) {
            $behaviors['audit-trail'] = [
                'class' => \bedezign\yii2\audit\AuditTrailBehavior::class
            ];
        }

        return $behaviors;
    }

    /**
     * overwrite \yii\db\BaseActiveRecord::instantiate() to be able to overload model classes returned by ActiveQuery
     *
     * The default implementation return new static() models, but Yii::createObject()
     * must be used to be able to overload model classes via DI
     *
     * @param array $row
     *
     * @return ActiveRecord|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function instantiate($row)
    {
        return \Yii::createObject(static::class);
    }

}
