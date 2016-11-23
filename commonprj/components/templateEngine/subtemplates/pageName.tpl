{if $app->response->data['isEntity']}
    {isEntity}
    {if $app->response->data['isEntity']}
        {$entity = $app->response->data['entity']}
        {$entity->name}
    {elseif !empty($app->response->data['emptyMsg'])}
        {$app->response->data['emptyMsg']}
    {/if}
{else}
    {$sectionName}
{/if}
