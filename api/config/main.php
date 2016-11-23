<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-api',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components'          => [
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 0 : 0,
            'targets'    => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error', 'warning'],
                    'logVars' => [],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'material/material',
                        'material/substance',

                        'construction/element',
                        'construction/process',
                        'construction/work',
                        'construction/tool',
                        'construction/method',
                        'construction/construction',
                        'construction/bracing-element',
                        'construction/bracing',

                        'engineeringSystem/element',
                        'engineeringSystem/subsystem',
                        'engineeringSystem/working-substance',
                        'engineeringSystem/conductor',
                        'engineeringSystem/control-element',
                        'engineeringSystem/sensor',
                        'engineeringSystem/covering',
                        'engineeringSystem/converter',
                        'engineeringSystem/accumulator',
                    ],
                    'tokens'        => [
                        '{id}'                 => '<id:\\d[\\d,]*>',
                        '{childElementId}'     => '<childElementId:\\d+>',
                        '{parentElementId}'    => '<parent_element_id:\\d+>',
                        '{rootId}'             => '<rootId:\\d+>',
                        '{inclusionElementId}' => '<inclusionElementId:\\d+>',
                        '{propertyId}'         => '<propertyId:\\d+>',
                        '{elementClassId}'     => '<elementClassId:\\d+>',
                        '{modelId}'            => '<modelId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'POST {id}/child/{childElementId}'         => 'createElementChild',
                        'POST {id}/inclusion/{inclusionElementId}' => 'createElementInclusion',
                        'POST {id}/property/{propertyId}'          => 'createElementPropertyRelation',
                        'POST {id}/element-class/{elementClassId}' => 'createElement2ElementClass',
                        'POST {id}/model'                          => 'createElementModel',

                        'GET {id}/element-classes'                      => 'viewElementElementClasses',
                        'GET {id}/children'                             => 'viewElementChildren',
                        'GET {id}/hierarchy'                            => 'viewElementHierarchy',
                        'GET {id}/inclusions'                           => 'viewElementInclusions',
                        'GET {id}/is-parent'                            => 'viewElementIsParent',
                        'GET {id}/parent'                               => 'viewElementParent',
                        'GET {id}/relation-groups'                      => 'viewElementRelationGroups',
                        'GET {id}/relation-classes'                     => 'viewElementRelationClasses',
                        'GET {id}/models'                               => 'viewElementModels',
                        'GET {id}/properties'                           => 'viewElementProperties',
                        'GET {id}/root'                                 => 'viewElementRootIds',
                        'GET {id}/property/{propertyId}'                => 'viewElementProperty',
                        'GET {id}/property/{propertyId}/property-value' => 'viewElementPropertyValue',
                        'GET find-by-properties'                        => 'viewElementsByPropertyValues',

                        'PUT {id}/property/{propertyId}' => 'updateElementPropertyRelation',

                        'DELETE {id}/child/{childElementId}'         => 'deleteElementChild',
                        'DELETE {id}/inclusion/{inclusionElementId}' => 'deleteElementInclusion',
                        'DELETE {id}/property/{propertyId}'          => 'deleteElementPropertyValue',
                        'DELETE {id}/element-class/{elementClassId}' => 'deleteElement2ElementClass',
                        'DELETE {id}/model/{modelId}'                => 'deleteElementModel',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => [
                        'common/element',
                        'common/property-type',
                    ],
                    'pluralize'  => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/property-unit',
                    ],
                    'tokens'        => [
                        '{id}'             => '<id:\\d[\\d,]*>',
                        '{propertyUnitId}' => '<propertyUnitId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/properties' => 'viewPropertiesByUnit',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/element-class',
                    ],
                    'tokens'        => [
                        '{id}'              => '<id:\\d[\\d,]*>',
                        '{elementClassId}'  => '<elementClassId:\\d+>',
                        '{className}'       => '<contextNameAndClassName:[A-Za-z]+\.[A-Za-z]+>',
                        '{relationClassId}' => '<relationClassId:\\d+>',
                        '{propertyId}'      => '<propertyId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'POST {id}/relation-class/{relationClassId}' => 'createElementClass2RelationClass',
                        'POST {id}/property/{propertyId}'            => 'createElementClass2Property',

                        'GET {id}/relation-classes' => 'viewElementClass2RelationClasses',
                        'GET {id}/properties'       => 'viewElementClassProperties',
                        'GET by-name/{className}'   => 'viewElementClassByName',
                        'GET {id}/context'          => 'viewElementClassContext',

                        'DELETE {id}/relation-class/{relationClassId}' => 'deleteElementClass2RelationClass',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/relation-group',
                    ],
                    'tokens'        => [
                        '{id}'              => '<id:\\d[\\d,]*>',
                        '{relationGroupId}' => '<relationGroupId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/relation-class' => 'viewRelationGroupRelationClass',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/element-type',
                    ],
                    'tokens'        => [
                        '{id}'            => '<id:\\d[\\d,]*>',
                        '{elementTypeId}' => '<elementTypeId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/element-category' => 'viewElementTypeCategory',
                        'GET {id}/element-class'    => 'viewElementTypeClass',
                        'GET {id}/variant'          => 'viewElementTypeVariant',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/element-category',
                    ],
                    'tokens'        => [
                        '{id}'                    => '<id:\\d[\\d,]*>',
                        '{elementCategoryId}'     => '<elementCategoryId:\\d+>',
                        '{rootElementCategoryId}' => '<rootElementCategoryId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/children'                     => 'viewElementCategoryChildren',
                        'GET {id}/parent'                       => 'viewElementCategoryParent',
                        'GET {id}/is-parent'                    => 'viewElementCategoryIsParent',
                        'GET {id}/root'                         => 'viewElementCategoryRoot',
                        'GET roots'                             => 'viewElementCategoryRoots',
                        'GET hierarchy/{rootElementCategoryId}' => 'viewElementCategoryHierarchy',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/relation-class',
                    ],
                    'tokens'        => [
                        '{id}'              => '<id:\\d[\\d,]*>',
                        '{relationClassId}' => '<relationClassId:\\d+>',
                        '{elementClassId}'  => '<elementClassId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'POST {id}/element-class/{elementClassId}' => 'createRelationClass2ElementClass',

                        'GET {id}/relation-groups' => 'viewRelationClassGroups',
                        'GET {id}/element-classes' => 'viewRelationClass2ElementClasses',

                        'DELETE {id}/element-class/{elementClassId}' => 'deleteRelationClass2ElementClass',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/relation',
                    ],
                    'tokens'        => [
                        '{id}' => '<id:\\d[\\d,]*>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/child' => 'viewRelationChilds',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/property',
                    ],
                    'tokens'        => [
                        '{id}'              => '<id:\\d[\\d,]*>',
                        '{propertyId}'      => '<propertyId:\\d+>',
                        '{elementClassId}'  => '<elementClassId:\\d+>',
                        '{propertyValueId}' => '<propertyValueId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'POST {id}/property-class/{elementClassId}' => 'createProperty2elementClass',

                        'GET {id}/property-class'  => 'viewProperty2elementClasses',
                        'GET {id}/property-values' => 'viewPropertyValues',
                        'GET {id}/property-unit'   => 'viewPropertyUnit',

                        'PUT {id}/property-class/{elementClassId}' => 'updateProperty2elementClass',

                        'DELETE {id}/property-value/{propertyValueId}' => 'deletePropertyValue',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/schema-element',
                    ],
                    'tokens'        => [
                        '{id}'              => '<id:\\d[\\d,]*>',
                        '{schemaElementId}' => '<schemaElementId:\\d+>',
                    ],
                    'extraPatterns' => [
                        'POST {id}/elements' => 'createElementsBySchemaId',

                        'GET {id}/elements' => 'viewElementsBySchemaId',
                        'GET {id}/variants' => 'viewElementVariantsBySchemaId',

                        'DELETE {id}/elements' => 'deleteElementsBySchemaId',
                        'DELETE {id}/variants' => 'deleteVariantsBySchemaId',
                    ],
                    'pluralize'     => false,
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => [
                        'common/variant',
                    ],
                    'tokens'        => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        '{variantId}' => '<id:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/elementType'    => 'viewVariantElementType',
                        'GET {id}/property'       => 'viewVariantProperty',
                        'GET {id}/propertyValue'  => 'viewVariantPropertyValue',
                        'GET {id}/relatedElement' => 'viewVariantRelatedElement',
                        'GET {id}/relation-class' => 'viewVariantRelationClass',
                        'GET {id}/schema-element' => 'viewVariantSchemaElement',
                    ],
                    'pluralize'     => false,
                ],
            ],
        ],

        'response' => [
            'format'  => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
    ],

    'params'  => $params,
    'modules' => [
        'common'            => [
            'class' => 'api\modules\common\Module',
        ],
        'material'          => [
            'class' => 'api\modules\material\Module',
        ],
        'construction'      => [
            'class' => 'api\modules\construction\Module',
        ],
        'engineeringSystem' => [
            'class' => 'api\modules\engineeringSystem\Module',
        ],
    ],
];
