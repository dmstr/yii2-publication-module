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
    public $showTitle = true;
    public $teaser = true;
    public $limit = null;


    /**
     * @return PublicationItem|string
     * @throws \Exception
     */
    public function run()
    {

        /** @var PublicationCategory $publicationCategory */
        $publicationCategory = PublicationCategory::findOne($this->categoryId);

        $html = null;

        $cssClass = 'publication-item-detail';

        if ($publicationCategory instanceof PublicationCategory || $this->item instanceof PublicationItem) {

            if ($this->categoryId !== null) {
                /** @var PublicationItem $publicationItemsBase */
                $publicationItemsBase = PublicationItem::find()->where(['category_id' => $this->categoryId])->published()->limit($this->limit)->all();

                $publicationItems = [];
                foreach ($publicationItemsBase as $publicationItemBase) {
                    $publicationItems[] = $publicationItemBase->getPublicationItemTranslations()->published()->one();
                }
                $cssClass = 'publication-item-index';

            } else {

                /** @var PublicationItem $item */
                $item = $this->item;
                $publicationItems[] = $item;
                $publicationCategory = $item->category;
            }

            foreach ($publicationItems as $publicationItem) {

                if ($this->teaser === true) {
                    $properties = Json::decode($publicationItem->teaser_widget_json);
                    // allow usage of content variables in teaser
                    $properties['content'] = Json::decode($publicationItem->content_widget_json);
                } else {
                    $properties = Json::decode($publicationItem->content_widget_json);
                }
                $publicationWidget = '';
                if ($this->showTitle === true) {
                    $publicationWidget .= "<h3 class='publication-title'>{$publicationItem->title}</h3>";
                }
                $publicationWidget .= $publicationCategory->render((array)$properties,$this->teaser);

                if ($this->teaser) {
                    $publicationWidget = Html::a($publicationWidget,['/publication/default/detail','itemId' => $publicationItem->id,'showTitle' => $this->showTitle],['class' => 'publication-detail-link']);
                }

                $html .= $publicationWidget;

            }
        }
        return "<div class='publication-widget {$cssClass}'>{$html}</div>";
    }

}