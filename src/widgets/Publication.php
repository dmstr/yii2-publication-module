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
use yii\data\Pagination;
use yii\widgets\LinkPager;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int categoryId
 * @property PublicationItem item
 */
class Publication extends BasePublication
{
    public $categoryId;
    public $item;


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
            $publicationItemsQuery = PublicationItem::find()->published()->limit($this->limit);
            $count = $publicationItemsQuery->count();
            $pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => 60, 'pageSizeLimit' => [1, 60]]);
            $publicationItemsBase = $publicationItemsQuery->orderBy(['release_date' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();

            $publicationItems = [];
            foreach ($publicationItemsBase as $publicationItemBase) {
                $publicationItems[] = $publicationItemBase->getTranslations()->published()->one();
            }

            $html = '';
            /** @var PublicationItemTranslation $publicationItemTranslation */
            foreach ($publicationItems as $publicationItemTranslation) {
                $html .= $this->renderHtmlByPublicationItem($publicationItemTranslation, $publicationItemTranslation->item->category);
            }
            return "<div class='publication-widget publication-item-index'>" . $html . '</div>' . '<div class="publication-pagination">' . LinkPager::widget([
                    'pagination' => $pagination,
                ]) . '</div>';
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

}