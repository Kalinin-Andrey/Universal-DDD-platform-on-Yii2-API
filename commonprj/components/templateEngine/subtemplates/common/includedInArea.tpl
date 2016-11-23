{*Подшаблон-селектор для получения элементов, в которые входит данный элемент*}
{if !empty($app->request->queryParams['UID'])}
    {isEntity}

    {if !empty($app->response->data['relationGroups'])}
        {$entity = $app->response->data['entity']}
        {$relationGroups = $app->response->data['relationGroups']}
        {$includedIn = []}
        {*Получение данных*}
        {foreach $relationGroups as $key => $relationGroup}
            {$root = $entity->getRoot($relationGroup['id'])}
            {if $root && $root->id !== $entity->id}
                {$includedIn[$key] = $root}
                {$images[$key] = $app->templateEngineHelper->getImageByElement($root)}
            {/if}
        {/foreach}

        {if $includedIn}
            {*Вывод родителей*}
            {$pathPiece = $app->response->data['pathPiece']}
            {include file="@commonprj/components/templateEngine/subtemplates/$pathPiece/relationArea/includedIn.tpl"}
        {/if}
    {/if}
{/if}