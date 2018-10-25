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
use dmstr\modules\publication\models\crud\PublicationItemTranslation;
use const PHP_EOL;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class Publication extends Widget
{
    public $categoryId;
    public $item;
    public $teaser = true;
    public $limit;


    /**
     * @return PublicationItem|string
     * @throws \Exception
     */
    public function run()
    {

        if ($this->categoryId) {
            /** @var PublicationCategory $publicationCategory */

            if ($this->categoryId === 'all') {
                $publicationCategories = PublicationCategory::find()->all();

            } else {
                $publicationCategories = PublicationCategory::findAll($this->categoryId);
            }



            $widgets = [];
            foreach ($publicationCategories as $publicationCategory) {

                $categoryId = $publicationCategory->id;

                if ($categoryId !== null) {
                    /** @var PublicationItem $publicationItemsBase */
                    $publicationItemsBase = PublicationItem::find()->where(['category_id' => $categoryId])->published()->limit($this->limit)->all();

                    $publicationItems = [];
                    foreach ($publicationItemsBase as $publicationItemBase) {
                        $publicationItems[] = $publicationItemBase->getPublicationItemTranslations()->published()->one();
                    }

                } else {

                    /** @var PublicationItem $item */
                    $item = $this->item;
                    $publicationItems[] = $item;
                    $publicationCategory = $item->category;
                }
                $html = '';
                foreach ($publicationItems as $publicationItem) {

                    $html .= $this->renderHtmlByPublicationItem($publicationItem,$publicationCategory);

                }
                $widgets[] = "<div class='publication-widget publication-item-index'>{$html}</div>";
            }
            return implode(PHP_EOL,$widgets);
        }

        if ($this->item) {

            $html = $this->renderHtmlByPublicationItem($this->item,$this->item->category);

            return "<div class='publication-widget publication-item-detail'>{$html}</div>";
        }

        return '';

    }

    /**
     * @param PublicationItem $publicationItem
     * @param PublicationCategory $publicationCategory
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderHtmlByPublicationItem($publicationItem, $publicationCategory) {
        if ($this->teaser) {
            $properties = Json::decode($publicationItem->teaser_widget_json);
            // allow usage of content variables in teaser
            $properties['content'] = Json::decode($publicationItem->content_widget_json);
        } else {
            $properties = Json::decode($publicationItem->content_widget_json);
        }

        $properties['model'] = $publicationItem instanceof PublicationItemTranslation ? $publicationItem->item : $publicationItem;

        $publicationWidget = '';


        $publicationWidget .= $publicationCategory->render((array)$properties, $this->teaser);

        if ($this->teaser) {
            $publicationWidget = Html::a($publicationWidget, ['/publication/default/detail', 'itemId' => $publicationItem->id], ['class' => 'publication-detail-link']);
        }

        return $publicationWidget;
    }

}