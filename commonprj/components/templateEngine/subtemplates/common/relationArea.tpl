{*Подшаблон-селектор для получения связей и подключения нужных подшаблонов-вывода*}
{if !empty($app->request->queryParams['UID'])}
    {isEntity}

    {if !empty($app->response->data['relationGroups'])}
        {$entity = $app->response->data['entity']}
        {$relationGroups = $app->response->data['relationGroups']}
        {$inclusions = [] scope="root"}
        {$hierarchies = [] scope="root"}
        {*Получение данных*}
        {foreach $relationGroups as $relationGroup}

            {if empty($relationClass) || $relationClass->id !== $relationGroup->relation_class_id}
                {$relationClass = $relationGroup->getRelationClass()}
            {/if}

            {$inclusion = $entity->getInclusions($relationGroup->id)}

            {if $inclusion}
                {$inclusions[$relationClass->id][$relationGroup->id]['inclusion'] = $inclusion}
                {$inclusions[$relationClass->id][$relationGroup->id]['relation_class'] = $relationClass}
            {/if}

            {if $entity->id === $relationGroup->root_id}
                {$hierarchy = $entity->getElementHierarchy($relationGroup['id'])}

                {if $hierarchy}
                    {$hierarchies[$relationClass->id][$relationGroup->id]['hierarchy'] = $hierarchy['children']}
                    {$hierarchies[$relationClass->id][$relationGroup->id]['relation_class'] = $relationClass}
                {/if}
            {/if}

        {/foreach}
        {$pathPiece = $app->response->data['pathPiece']}

        {if $inclusions}
            {*Вывод инклюзий*}
            {include file="@commonprj/components/templateEngine/subtemplates/$pathPiece/relationArea/inclusions.tpl"}
        {/if}

        {if $hierarchies}
            {*Вывод иерархий*}
            {include file="@commonprj/components/templateEngine/subtemplates/$pathPiece/relationArea/hierarchy.tpl"}
        {/if}
    {/if}
{/if}