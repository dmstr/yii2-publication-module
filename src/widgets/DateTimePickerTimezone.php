<?php

namespace dmstr\modules\publication\widgets;

use dmstr\modules\publication\widgets\assets\UtcToMezAsset;
use Yii;
use yii\helpers\Html;
use zhuravljov\yii\widgets\DateTimePicker;

class DateTimePickerTimezone extends DateTimePicker
{
    public function init()
    {
        parent::init();

        $this->field->hint(
            Yii::t('publication', 'Selected UTC end date time corresponds to CEST {offset} hours', ['offset' => Html::tag('span', null, ['class' => 'timezone-offset'])]), [
            'class' => ['help-block', 'timezone-offset-info-text']
        ]);
        $this->options['data-date-time-picker-timezone'] = $this->hasModel() ? $this->attribute : $this->name;
        $this->clientOptions['autoclose'] = true;

        UtcToMezAsset::register($this->view);
    }

}
