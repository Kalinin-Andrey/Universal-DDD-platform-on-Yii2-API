<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 26.07.2016
 */

namespace commonprj\components\templateEngine\entities\subtemplate;


use commonprj\components\templateEngine\entities\EntitiesRepository;

/**
 * Class SubtemplateRepository
 * @package commonprj\components\templateEngine\entities\subtemplate
 */
class SubtemplateRepository extends EntitiesRepository
{
    protected $entityClassName = 'commonprj\components\templateEngine\entities\subtemplate\Subtemplate';
    protected $recordClassName = 'commonprj\components\templateEngine\models\SubtemplateRecord';
}