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
use yii\data\Pagination;
use yii\widgets\LinkPager;


/**
 * Class Publication
 * @package dmstr\modules\publication\widgets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property int categoryId
 * @property PublicationItem item
 * @property bool pagination
 * @property string wrapperCssClass
 */
class Publication extends BasePublication
{
    public $categoryId;
    public $item;
    public $pagination = false;

    private $wrapperCssClass = 'publication-widget publication-item-index';


    /**
     * @return PublicationItem|string
     * @throws \Exception
     */
    public function run()
    {

        if ($this->item) {
            return "<div class='{$this->wrapperCssClass}'>" . $this->renderHtmlByPublicationItem($this->item) . '</div>';
        }

        // the base Query is defined to be sure we always have the base constrains
        // for different cases use a clone of this!!!!
        $publicationItemsBaseQuery = PublicationItem::find()->published()->limit($this->limit);

        $paginationHtml = null;

        if ($this->categoryId === 'all') {

            if ($this->pagination) {
                $publicationItemsQuery = clone $publicationItemsBaseQuery;
                $count = $publicationItemsQuery->count();
                $pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => 60, 'pageSizeLimit' => [1, 60]]);
                $publicationItemsQuery->offset($pagination->offset);
                $paginationHtml = '<div class="publication-pagination">' . LinkPager::widget([
                        'pagination' => $pagination,
                    ]) . '</div>';
            }

            $publicationItemsQuery = clone $publicationItemsBaseQuery;
            $html = $this->renderItemsHtml($publicationItemsQuery->all());

        } else {
            /** @var PublicationCategory[] $publicationCategories */
            $publicationCategories = PublicationCategory::find()->andWhere(['id' => $this->categoryId])->all();

            $widgets = [];
            foreach ($publicationCategories as $publicationCategory) {
                $publicationItemsQuery = clone $publicationItemsBaseQuery;
                $publicationItems = $publicationItemsQuery->andWhere(['category_id' => $publicationCategory->id])->all();
                $widgets[] = $this->renderItemsHtml($publicationItems);
            }

            $html = implode(PHP_EOL, $widgets);
        }

        return "<div class='{$this->wrapperCssClass}'>" . $html . '</div>' . $paginationHtml;
    }

    protected function renderItemsHtml($publicationItems = [])
    {
        $html = '';
        /** @var PublicationItem[] $publicationItems */
        foreach ($publicationItems as $publicationItem) {
            $html .= $this->renderHtmlByPublicationItem($publicationItem);
        }
        return $html;
    }

}