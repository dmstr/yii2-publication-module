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
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int categoryId
 * @property bool teaser
 * @property int limit
 * @property PublicationItem item
 */
class Publication extends Widget
{
    public $categoryId;
    public $teaser = true;
    public $item;
    public $limit;


    /**
     * @return PublicationItem|string
     * @throws \Exception
     */
    public function run()
    {

        if ($this->item) {
            return "<div class='publication-widget publication-item-index'>" . $this->renderHtmlByPublicationItem($this->item, $this->item->category) . '</div>';
        }
        /** @var PublicationCategory $publicationCategory */

        if ($this->categoryId === 'all') {
            /** @var PublicationItem $publicationItemsBase */
            $publicationItemsBase = PublicationItem::find()->published()->limit($this->limit)->orderBy(['release_date' => SORT_DESC])->all();

            $publicationItems = [];
            foreach ($publicationItemsBase as $publicationItemBase) {
                $publicationItems[] = $publicationItemBase->getTranslations()->published()->one();
            }

            $html = '';
            /** @var PublicationItemTranslation $publicationItemTranslation */
            foreach ($publicationItems as $publicationItemTranslation) {
                $html .= $this->renderHtmlByPublicationItem($publicationItemTranslation, $publicationItemTranslation->item->category);
            }
            return "<div class='publication-widget publication-item-index'>" . $html . '</div>';
        }

        $publicationCategories = PublicationCategory::findAll($this->categoryId);

        $widgets = [];
        foreach ($publicationCategories as $publicationCategory) {

            $categoryId = $publicationCategory->id;


            /** @var PublicationItem $publicationItemsBase */
            $publicationItemsBase = PublicationItem::find()->where(['category_id' => $categoryId])->published()->limit($this->limit)->orderBy(['release_date' => SORT_DESC])->all();

            $publicationItems = [];
            foreach ($publicationItemsBase as $publicationItemBase) {
                $publicationItems[] = $publicationItemBase->getTranslations()->published()->one();
            }

            $html = '';
            foreach ($publicationItems as $publicationItem) {
                $html .= $this->renderHtmlByPublicationItem($publicationItem, $publicationCategory);
            }
            $widgets[] = $html;
        }
        return "<div class='publication-widget publication-item-index'>" . implode(PHP_EOL, $widgets) . '</div>';

    }

    /**
     * @param PublicationItem|PublicationItemTranslation $publicationItem
     * @param PublicationCategory $publicationCategory
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \yii\base\InvalidConfigException
     */
    public function renderHtmlByPublicationItem($publicationItem, $publicationCategory)
    {
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