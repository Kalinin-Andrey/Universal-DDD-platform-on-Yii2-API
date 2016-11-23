{*Подшаблон селектор для вывода предков первого уровня*}
{if $app->response->data['isEntity']}
    {isEntity}

    {if !empty($app->response->data['relationGroupsWhereChildren'])}
        {$entity = $app->response->data['entity']}
        {$relationGroups = $app->response->data['relationGroupsWhereChildren']}
        {$relationClasses = $app->response->data['relationClassesWhereChildren']}
        {*<br>*}
        {$parents = []}
        {*Получение данных*}
        {foreach $relationGroups as $relationGroup}
            {if isset($relationClasses[$relationGroup->relationClassId])}
                {$relationClass = $relationClasses[$relationGroup->relationClassId]}
                {$parent = []}
                {$relationTypeId = $relationClass->relationTypeId}

                {if $relationTypeId === 1}
                    {$parent = $entity->getParent($relationGroup->id)}
                {elseif $relationTypeId === 2}
                    {$parent = $entity->getRoot($relationGroup->id)}
                {/if}

                {if $parent}
                    {$parents[$relationGroup->relationClassId]['relationGroups'][$relationGroup->id] = $parent}
                    {$parents[$relationGroup->relationClassId]['relationClass'] = $relationClass}
                {/if}
            {/if}
        {/foreach}

        {if $parents}
            {*Вывод потомков*}
            {$pathPiece = $app->response->data['pathPiece']}
            {include file="@commonprj/components/templateEngine/subtemplates/$pathPiece/relationArea/parents.tpl"}
        {/if}
    {/if}
{/if}