<?php
/**
 * /app/src/../runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\controllers\crud\actions\DeleteAttachment;
use dmstr\modules\publication\models\crud\PublicationTag;
use dmstr\modules\publication\models\crud\search\PublicationTag as PublicationTagSearch;

/**
 * This is the class for controller "PublicationTagController".
 */
class PublicationTagController extends BaseController
{

    public $model = PublicationTag::class;
    public $searchModel = PublicationTagSearch::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete-item-attachment'] = DeleteAttachment::class;
        return $actions;
    }
}
