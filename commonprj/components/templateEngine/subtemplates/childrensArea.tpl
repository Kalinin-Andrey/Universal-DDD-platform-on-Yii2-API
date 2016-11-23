{*Подшаблон селектор для вывода потомков первого уровня*}
{if $app->response->data['isEntity']}
    {isEntity}

    {if !empty($app->response->data['relationGroups'])}
        {$entity = $app->response->data['entity']}
        {$relationGroups = $app->response->data['relationGroups']}
        {$relationClasses = $app->response->data['relationClassesWhereParent']}
        {$childrens = []}
        {*Получение данных*}
        {foreach $relationGroups as $relationGroup}
            {if isset($relationClasses[$relationGroup->relationClassId])}
                {$relationClass = $relationClasses[$relationGroup->relationClassId]}
                {$children = []}

                {if $relationClass->relationTypeId === 1}
                    {$children = $entity->getChildren($relationGroup->id)}
                {elseif $relationClass->relationTypeId === 2}
                    {$children = $entity->getInclusions($relationGroup->id)}
                {/if}

                {if $children}
                    {$childrens[$relationClass->id]['relationGroups'][$relationGroup->id] = $children}
                    {$childrens[$relationClass->id]['relationClass'] = $relationClass}
                {/if}
            {/if}
        {/foreach}

        {if $childrens}
            {*Вывод потомков*}
            {$pathPiece = $app->response->data['pathPiece']}
            {include file="@commonprj/components/templateEngine/subtemplates/$pathPiece/relationArea/childrens.tpl"}
        {/if}
    {/if}
{/if}