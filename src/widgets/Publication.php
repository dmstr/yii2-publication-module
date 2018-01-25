<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\publication\widgets;

use dmstr\modules\publication\models\crud\PublicationCategory;
use dmstr\modules\publication\models\crud\PublicationItem;
use hrzg\widget\models\crud\WidgetContent;
use yii\base\Widget;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class Publication extends Widget
{
    public $categoryId;
    public $teaser = true;
    public $limit = null;

    /**
     * @return PublicationItem|string
     * @throws \Exception
     */
    public function run()
    {
//        /** @var PublicationItem $publicationItem */
//        $publicationItems = PublicationItem::find()->where(['publication_category_id' => $this->categoryId])->published()->all();
//
//        $html = null;
//
//        foreach ($publicationItems as $publicationItem) {
//            if ($publicationItem === null) {
//                return $publicationItem;
//            }
//
//            /** @var PublicationCategory $publicationCategory */
//            $publicationCategory = $publicationItem->getPublicationCategory()->one();
//
//            $widgetTemplateId = $this->teaser === true ? $publicationCategory->teaser_widget_template_id : $publicationCategory->content_widget_template_id;
//
//            $widgets = WidgetContent::find()->where(['widget_template_id' => $widgetTemplateId])->andWhere(['status' => '1'])->limit($this->limit)->all();
//
//            /** @var WidgetContent[] $widgets */
//            foreach ($widgets as $widget) {
//                $html .= DisplayWidgetContent::widget(['widget' => $widget]);
//            }
//        }
//        return "<div class='publication-widget'>{$html}</div>";
    }

}