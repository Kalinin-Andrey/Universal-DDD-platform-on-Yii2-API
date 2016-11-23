<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'                           => [
            'class' => 'yii\caching\FileCache',
        ],
        'route'                           => [
            'class'          => 'commonprj\services\Route',
            'subsystemNames' => [
                'core'            => 'core',
                'ecomm'           => 'ecomm',
                'encyclopedia'    => 'enc',
                'image'           => 'img',
                'template_engine' => 'tpl',
            ],
        ],
        'abstractPropertyValueRepository' => [
            'class' => 'commonprj\components\core\entities\common\abstractPropertyValue\AbstractPropertyValueDBRepository',
        ],
        'booleanPropertyRepository'       => [
            'class' => 'commonprj\components\core\entities\common\booleanProperty\BooleanPropertyDBRepository',
        ],
        'datePropertyRepository'          => [
            'class' => 'commonprj\components\core\entities\common\dateProperty\DatePropertyDBRepository',
        ],
        'elementRepository'               => [
            'class' => 'commonprj\components\core\entities\common\element\ElementDBRepository',
        ],
        'elementCategoryRepository'       => [
            'class' => 'commonprj\components\core\entities\common\elementCategory\ElementCategoryDBRepository',
        ],
        'elementClassRepository'          => [
            'class' => 'commonprj\components\core\entities\common\elementClass\ElementClassDBRepository',
        ],
        'elementTypeRepository'           => [
            'class' => 'commonprj\components\core\entities\common\elementType\ElementTypeDBRepository',
        ],
        'floatPropertyRepository'         => [
            'class' => 'commonprj\components\core\entities\common\floatProperty\FloatPropertyDBRepository',
        ],
        'geolocationPropertyRepository'   => [
            'class' => 'commonprj\components\core\entities\common\geolocationProperty\GeolocationPropertyDBRepository',
        ],
        'intPropertyRepository'           => [
            'class' => 'commonprj\components\core\entities\common\intProperty\IntPropertyDBRepository',
        ],
        'listItemPropertyRepository'      => [
            'class' => 'commonprj\components\core\entities\common\listItemProperty\ListItemPropertyDBRepository',
        ],
        'modelRepository'                 => [
            'class' => 'commonprj\components\core\entities\common\model\ModelDBRepository',
        ],
        'propertyRepository'              => [
            'class' => 'commonprj\components\core\entities\common\property\PropertyDBRepository',
        ],
        'propertyRangeRepository'         => [
            'class' => 'commonprj\components\core\entities\common\propertyRange\PropertyRangeDBRepository',
        ],
        'propertyTypeRepository'          => [
            'class' => 'commonprj\components\core\entities\common\propertyType\PropertyTypeDBRepository',
        ],
        'relationClassRepository'         => [
            'class' => 'commonprj\components\core\entities\common\relationClass\RelationClassDBRepository',
        ],
        'relationGroupRepository'         => [
            'class' => 'commonprj\components\core\entities\common\relationGroup\RelationGroupDBRepository',
        ],
        'stringPropertyRepository'        => [
            'class' => 'commonprj\components\core\entities\common\stringProperty\StringPropertyDBRepository',
        ],
        'textPropertyRepository'          => [
            'class' => 'commonprj\components\core\entities\common\textProperty\TextPropertyDBRepository',
        ],
        'timeStampPropertyRepository'     => [
            'class' => 'commonprj\components\core\entities\common\timeStampProperty\TimeStampPropertyDBRepository',
        ],
        'bracingRepository'               => [
            'class' => 'commonprj\components\core\entities\construction\bracing\BracingDBRepository',
        ],
        'bracingElementRepository'        => [
            'class' => 'commonprj\components\core\entities\construction\bracingElement\BracingElementDBRepository',
        ],
        'constructionRepository'          => [
            'class' => 'commonprj\components\core\entities\construction\construction\ConstructionDBRepository',
        ],
        'conElementRepository'            => [
            'class' => 'commonprj\components\core\entities\construction\element\ElementDBRepository',
        ],
        'methodRepository'                => [
            'class' => 'commonprj\components\core\entities\construction\method\MethodDBRepository',
        ],
        'processRepository'               => [
            'class' => 'commonprj\components\core\entities\construction\process\ProcessDBRepository',
        ],
        'toolRepository'                  => [
            'class' => 'commonprj\components\core\entities\construction\tool\ToolDBRepository',
        ],
        'workRepository'                  => [
            'class' => 'commonprj\components\core\entities\construction\work\WorkDBRepository',
        ],
        'accumulatorRepository'           => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\accumulator\AccumulatorDBRepository',
        ],
        'conductorRepository'             => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\conductor\ConductorDBRepository',
        ],
        'controlElementRepository'        => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\controlElement\ControlElementDBRepository',
        ],
        'converterRepository'             => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\converter\ConverterDBRepository',
        ],
        'coveringRepository'              => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\covering\CoveringDBRepository',
        ],
        'esElementRepository'             => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\element\ElementDBRepository',
        ],
        'esModelRepository'               => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\model\ModelDBRepository',
        ],
        'sensorRepository'                => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\sensor\SensorDBRepository',
        ],
        'subsystemRepository'             => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\subsystem\SubsystemDBRepository',
        ],
        'workingSubstanceRepository'      => [
            'class' => 'commonprj\components\core\entities\engineeringSystem\workingSubstance\WorkingSubstanceDBRepository',
        ],
        'IMmodelRepository'               => [
            'class' => 'commonprj\components\core\entities\immovables\model\ModelDBRepository',
        ],
        'objectRepository'                => [
            'class' => 'commonprj\components\core\entities\immovables\object\ObjectDBRepository',
        ],
        'phaseRepository'                 => [
            'class' => 'commonprj\components\core\entities\immovables\phase\PhaseDBRepository',
        ],
        'assemblageRepository'            => [
            'class' => 'commonprj\components\core\entities\interior\assemblage\AssemblageDBRepository',
        ],
        'INelementRepository'             => [
            'class' => 'commonprj\components\core\entities\interior\element\ElementDBRepository',
        ],
        'functionalZoneRepository'        => [
            'class' => 'commonprj\components\core\entities\interior\functionalZone\FunctionalZoneDBRepository',
        ],
        'roomRepository'                  => [
            'class' => 'commonprj\components\core\entities\interior\room\RoomDBRepository',
        ],
        'solutionRepository'              => [
            'class' => 'commonprj\components\core\entities\interior\solution\SolutionDBRepository',
        ],
        'materialRepository'              => [
            'class' => 'commonprj\components\core\entities\material\material\MaterialDBRepository',
        ],
        'substanceRepository'             => [
            'class' => 'commonprj\components\core\entities\material\substance\SubstanceDBRepository',
        ],
        'propertyVariantRepository'             => [
            'class' => 'commonprj\components\core\entities\common\propertyVariant\PropertyVariantDBRepository',
        ],
        'relationVariantRepository'             => [
            'class' => 'commonprj\components\core\entities\common\relationVariant\RelationVariantDBRepository',
        ],
        'relationRepository'             => [
            'class' => 'commonprj\components\core\entities\common\relation\RelationDBRepository',
        ],
    ],
];
