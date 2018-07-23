<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use yii\db\ActiveRecord;
use yii\grid\Column;
use yii\helpers\Html;


/**
 * Class ActiveStatusColumn
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string attribute
 * @property string|array endpoint
 * @property string activeValue
 * @property string inputName
 * @property string valueAttribute
 * @property bool submitOnClick
 *
 */
class ActiveStatusColumn extends Column
{


    public $attribute = 'status';
    public $endpoint;

    public $activeValue;

    public $inputName = 'entryId';
    public $name = 'entryId';
    public $valueAttribute = 'id';

    public $submitOnClick = true;
    /**
     * @param ActiveRecord $model
     * @param int $key
     * @param int $index
     * @return string
     */
    public function renderDataCellContent($model, $key, $index)
    {
        $formId = 'form-' . $index;

        $form = Html::beginForm($this->endpoint, 'post', ['id' => $formId]);
        $form .= Html::checkbox($this->inputName, false, [
            'onclick' => $this->submitOnClick ? "document.getElementById('{$formId}').submit();" : false,
            'value' => $model->{$this->valueAttribute},
            // checked funktioniert noch nicht!
            'checked' => $model->{$this->attribute} === $this->activeValue ? 'checked' : false
        ]);
        $form .= Html::endForm();

        return $form;
    }


}