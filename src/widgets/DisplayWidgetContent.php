<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use hrzg\widget\models\crud\WidgetContent;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;


/**
 * Class DisplayWidgetContent
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property WidgetContent $widget
 */
class DisplayWidgetContent extends Widget
{
    public $widget;

    /**
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (!($this->widget instanceof WidgetContent)) {
            throw new InvalidConfigException(Yii::t('publication', '\$widget must be an instance of hrzg\widget\models\crud\WidgetContent'));
        }
        $widget = $this->widget;
        $properties = Json::decode($widget->default_properties_json);
        $class = Yii::createObject($widget->template->php_class);
        $class->setView($widget->getViewFile());

        if ($properties) {
            $class->setProperties($properties);
        }

        $html = Html::beginTag('div',
            ['id' => 'widget-' . ($widget->name_id ?: $widget->id), 'class' => 'hrzg-widget-widget']);
        $html .= $class->run();
        $html .= Html::endTag('div');

        return $html;
    }
}