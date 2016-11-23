 {*Подшаблон для вывода иерархии
Доступные переменные:
$hierarchies - массив с массивами иерархий, сгруппированными по RelationGroup, ключи массива - RelationGroupId
$hierarchies[$relationClassId][$relationGroupId] =
[
    'hierarchies' => common\Element[] $hierarchies,
    'relation_class' => RelationClass $relationClass
]
*}
<div class="container">
    <div class="row">
        {foreach $hierarchies as $relationClassId => $relationGroup}
            {foreach $relationGroup as $relationGroupId => $arItem}
                <div class="col-lg-6">
                    <h4 class="text-center">{$arItem['relation_class']->name}</h4>
                    <hr>
                    {recursiveHierarchy hierarchy=$arItem['hierarchy'] type="ul"}
                </div>
                {if $arItem@iteration % 2 == 0}
            </div>
            <hr>
            <div class="row">
                {/if}
            {/foreach}
        {/foreach}
    </div>
</div>



