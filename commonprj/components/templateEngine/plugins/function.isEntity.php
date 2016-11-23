<?php
/**
 * Created by Arlanov.Alexandr.
 * Date: 23.08.2016
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 * @throws Exception
 * Плагин, проверяет есть ли в response->data entity, если нет, создает и записывает.
 * Получает, проверяет нужные во многих шаблонах данные, записывает их в response
 */

use \commonprj\components\core\entities\common\element\Element;
use \commonprj\components\core\entities\common\elementClass\ElementClass;

/**
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 */
function smarty_function_isEntity(array $params, Smarty_Internal_Template &$smarty)
{
    //Делаем все это только один раз
    if (empty(Yii::$app->response->data['entity'])) {
        $model = [];
        $requestParams = [
            'with' => 'relationGroups',
        ];
        //Получаем елемент по UID
        if (Yii::$app->response->data['isEntity'] === 1) {
            $model = Yii::$app->byUIDFactory->create(Yii::$app->request->queryParams['UID'], $requestParams);
            //или фильтр по свойствам
        } elseif (Yii::$app->response->data['isEntity'] === 2) {
            $params = Yii::$app->request->queryParams['queryParams'];
            $className = $params['find-by-properties'];
            //Кладем часть пути в response
            Yii::$app->response->data['pathPiece'] = $className;
            unset($params['find-by-properties']);
            $requestParams = array_merge($requestParams, $params);
            $repositoryName = Yii::$app->templateEngineHelper->getRepositoryName($className);
            $arModel = Yii::$app->{$repositoryName}->getElementsByPropertyValues($requestParams);

            //Берем первый элемент
            foreach ($arModel as $item) {
                $model = $item;
                break;
            }

            if (!$model) {
                Yii::$app->response->data['isEntity'] = 0;
                Yii::$app->response->data['emptyMsg'] = 'Sorry, we don\'t find anything by this filter!';
            }
            //var_dump(Yii::$app->response->data);die;
        }
        Yii::$app->response->data['entity'] = $model;
        //Если объект принадлежит к Element, или его потомкам, то нам почти всегда нужны связанные группы, изображения
        // и свойства
        if ($model instanceof Element) {
            $relationGroups = $model->relationGroups;
            //Получаем группы связей, где элемент является потомком
            $relations = Yii::$app->relationRepository->find(['childElementId' => $model->id, 'with' => 'relationGroup']);
            $groups = [];
            //Забираем только группы связей
            foreach ($relations as $relation) {
                $groups[(int)$relation->relationGroupId] = $relation->relationGroup;
            }
            //Кладем группы в response, чтобы использовать их в подшаблонах
            Yii::$app->response->data['relationGroups'] = $relationGroups;
            Yii::$app->response->data['relationGroupsWhereChildren'] = $groups;
            //Если зашли по UID
            if (!empty(Yii::$app->request->queryParams['className'])) {
                //Берем, положенный в контроллере параметр, который является определяющей частью пути к подшаблону
                $pathPiece = Yii::$app->request->queryParams['className'];
                //Кладем часть пути в response
                Yii::$app->response->data['pathPiece'] = $pathPiece;
                //если зашли с фильтра
            } else {
                $pathPiece = Yii::$app->response->data['pathPiece'];
            }

            //Получаем класс элемента вида context.Class
            $contextDotClass = Yii::$app->templateEngineHelper->getContextDotClass($pathPiece);

            //Получаем классы связей где элемент родитель и где потомок
            /** @var ElementClass $elementClass */
            $elementClass = Yii::$app->elementClassRepository->getElementClassByName($contextDotClass);
            $relationClassesWhereParent = $elementClass->getRelationClassesByIsRoot(true);
            Yii::$app->response->data['relationClassesWhereParent'] = $relationClassesWhereParent;
            $relationClassesWhereChildren = $elementClass->getRelationClassesByIsRoot(false);
            Yii::$app->response->data['relationClassesWhereChildren'] = $relationClassesWhereChildren;
            //Получаем свойства, находим все возможные значения и значение элемента, наполняем массив для вывода
            $propResult = [];
            $properties = $model->getProperties();
            //Если у элемента нет изображнения, добавляем путь к заглушке из params
            $imgPath = Yii::$app->params['noImagePath'];

            if ($properties) {
                $imageTypeId = Yii::$app->propertyRepository->getPropertyTypeIdByName('Image');
                //TODO второй вариант отбора изображений, понадобиться в рамках оптимизации обращений к АПИ, но только если не надо будет проходить в цикле по свойствам
//                $imageProperties = Yii::$app->propertyRepository->find(['propertyTypeId' => $imageTypeId]);
//
//                foreach ($imageProperties as $key => $imageProperty) {
//
//                    if (array_key_exists($imageProperty->id, $properties)) {
//                        $imageProperties[$key] = $properties[$imageProperty->id];
//                        unset($properties[$imageProperty->id]);
//                    }
//                }
                $elementSearchByPropertyData = [];

                foreach ($properties as $property) {
                    $propValue = $model->getPropertyValue($model->id, $property->id);
                    //Если свойство является изображением
                    if ($property->propertyTypeId === $imageTypeId) {
                        //и оно не пустое подменяем заглушку на путь к изображению
                        if (null !== $propValue->value) {
                            $imgPath = Yii::$app->imageService->getUrlById($propValue->value);
                        }
                        //TODO array of Image
                    } else {
                        $propResult[$property->id]['property'] = $property;
                        $propResult[$property->id]['allValues'] = $property->getValues();
                        $propResult[$property->id]['value'] = $propValue;
                        //Чтобы не делать лишних проверок в шаблоне, по умолчанию единица измерения равна пустой строке
                        $propResult[$property->id]['unit'] = '';
                        //Если нужно получаем propertyUnit
                        if (null !== $property->propertyUnitId) {
                            $arUnit = $property->getPropertyUnit();

                            if ($arUnit) {
                                $propResult[$property->id]['unit'] = $arUnit['name'];
                            }
                        }
                        //Массив со значениями свойств для фильтра по свойствам
                        $elementSearchByPropertyData[$property->id]['propertySysname'] = $property->sysname;

                        if ($propValue->multiplicityId === 1) {
                            $elementSearchByPropertyData[$property->id]['propertyValue'] = $propValue->value;

                        } elseif ($propValue->multiplicityId === 2) {
                            $elementSearchByPropertyData[$property->id]['propertyValue']['fromValue'] = $propValue->fromValue;
                            $elementSearchByPropertyData[$property->id]['propertyValue']['toValue'] = $propValue->toValue;

                        } elseif ($propValue->multiplicityId === 3) {
                            $elementSearchByPropertyData[$property->id]['propertyValue'] = $propValue->values;
                        }
                    }
                }
                //Получаем и заполняем queryString фильтра по свойствам
                foreach ($propResult as $propId => $item) {
                    $itemValue = $item['value'];

                    foreach ($item['allValues']['propertyValuesByMultiplicityId'] as $multiplicityId => $values) {
                        //Перебираем каждое возможное значение свойства
                        foreach ($values as $key => $value) {
                            $queryString = '';
                            //Добавляем ссылку только на значения других элементов
                            if ($value->id !== $itemValue->id) {
                                $searchData = $elementSearchByPropertyData;
                                //Меняем в массиве со значениями свойств текущего элемента, на значение искомого
                                if ($value->multiplicityId === 1) {
                                    $searchData[$propId]['propertyValue'] = $value->value;

                                } elseif ($value->multiplicityId === 2) {
                                    $searchData[$propId]['propertyValue']['fromValue'] = $value->fromValue;
                                    $searchData[$propId]['propertyValue']['toValue'] = $value->toValue;

                                } elseif ($value->multiplicityId === 3) {
                                    $searchData[$propId]['propertyValue'] = $value->values;
                                }
                                $searchDataCount = count($searchData);
                                $i = 1;
                                //Сцепляем строку для ссылки
                                foreach ($searchData as $data) {

                                    if (is_array($data['propertyValue'])) {
                                        $dataCount = count($data['propertyValue']);
                                        $ii = 1;

                                        foreach ($data['propertyValue'] as $k => $item) {
                                            $queryString .= $data['propertySysname'] . '[' . $k . ']=' . $item;

                                            if ($ii++ < $dataCount) {
                                                $queryString .= '&';
                                            }
                                        }
                                    } else {
                                        $queryString .= $data['propertySysname'] . '=' . $data['propertyValue'];
                                    }

                                    if ($i++ < $searchDataCount) {
                                        $queryString .= '&';
                                    }
                                }
                            }
                            //Перезаполняем массив со всеми значениями свойств
                            $propResult[$propId]['allValues']['propertyValuesByMultiplicityId'][$multiplicityId][$key] = [
                                'id'                 => $value->id,
                                'label'              => $value->label,
                                'value'              => $value->value,
                                'name'               => $value->name,
                                'fromValue'          => $value->fromValue,
                                'toValue'            => $value->toValue,
                                'values'             => $value->values,
                                'filterByProperties' => $queryString,
                            ];
                        }
                    }
                }
            } //if ($properties)
            //Кладем полученные данные в response, чтобы использовать их в подшаблонах
            Yii::$app->response->data['properties'] = $propResult;
            Yii::$app->response->data['image'] = $imgPath;
        }
    }
}