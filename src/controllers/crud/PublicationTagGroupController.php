<?php


namespace dmstr\modules\publication\controllers\crud;

use dmstr\modules\publication\models\crud\PublicationTagGroup;
use dmstr\modules\publication\models\crud\search\PublicationTagGroup as PublicationTagGroupSearch;

/**
 * This is the class for controller "PublicationTagController".
 */
class PublicationTagGroupController extends BaseController
{

    public $model = PublicationTagGroup::class;
    public $searchModel = PublicationTagGroupSearch::class;
}
