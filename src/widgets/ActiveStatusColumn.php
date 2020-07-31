<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use rmrevin\yii\fontawesome\FA;
use yii\db\ActiveRecord;
use yii\grid\Column;
use yii\helpers\Html;


/**
 * Toggle between to types of states
 *
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string attribute
 * @property string|array endpoint
 * @property string activeValue
 * @property string inputName
 * @property string valueAttribute
 *
 * @property string labelChecked
 * @property string labelUnchecked
 *
 */
class ActiveStatusColumn extends Column
{
    /**
     * Name of the status attribute
     */
    public $attribute = 'status';

    /**
     * Url to which the request will be send. Can be either a string or an array.
     */
    public $endpoint;

    /**
     * Attribute name which will be checked against the defined attribute attribute to check if status is true or false
     */
    public $activeValue;

    /**
     * Define input name so you can handle thing on the defined endpoint action
     */
    public $inputName = 'entryId';

    /**
     * Defines the value which will be posted as a value form with inputName as key to the endpoint
     */
    public $valueAttribute = 'id';

    /**
     * Defined label for status checked. Default is a icon.
     */
    public $labelChecked;

    /**
     * Defined label for status unchecked. Default is a icon.
     */
    public $labelUnchecked;

    /**
     *
     * @param ActiveRecord $model
     * @param int $key
     * @param int $index
     * @return string
     */
    public function renderDataCellContent($model, $key, $index)
    {
        // Check if status checked is true or false and use either the default label or the label defined in the labelChecked or labelUnchecked attribute

        if ($model->{$this->attribute} === $this->activeValue) {
            $label = $this->labelChecked ?? (string)FA::icon(FA::_CHECK);
        } else {
            $label = $this->labelUnchecked ?? (string)FA::icon(FA::_TIMES);
        }

        return Html::a($label , $this->endpoint, [
            'class' => ['status-toggle', 'btn-' . ($model->{$this->attribute} === $this->activeValue ? 'success' : 'danger')],
            'data' => [
                'method' => 'post',
                'params' => [$this->inputName => $model->{$this->valueAttribute}],
            ]
        ]);
    }


}