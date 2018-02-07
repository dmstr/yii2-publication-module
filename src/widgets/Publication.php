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
use hrzg\widget\widgets\TwigTemplate;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\twig\ViewRenderer;


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


        if ($publicationCategory instanceof PublicationCategory || $this->item instanceof PublicationItem) {

            if ($this->categoryId !== null) {
                /** @var PublicationItem $publicationItem */
                $publicationItems = PublicationItem::find()->where(['publication_category_id' => $this->categoryId])->published()->limit($this->limit)->all();
            } else {
                $publicationItems[] = $this->item;
                $publicationCategory = $this->item->publicationCategory;
            }

            foreach ($publicationItems as $publicationItem) {

                if ($this->teaser === true) {
                    $properties = Json::decode($publicationItem->teaser_widget_json);
                } else {
                    $properties = Json::decode($publicationItem->content_widget_json);
                }

                $publicationWidget = $publicationCategory->render($properties,$this->teaser);

                if ($this->teaser) {
                    $publicationWidget = Html::a($publicationWidget,['/publication/default/detail','itemId' => $publicationItem->id]);
                }

                $html .= $publicationWidget;

            }
        }
        return "<div class='publication-widget'>{$html}</div>";
    }

}