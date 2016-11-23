{*Подшаблон-селектор для получения свойств и подключения нужных подшаблонов-вывода*}
{if $app->response->data['isEntity']}
    {isEntity}
    {$entity = $app->response->data['entity']}
    {$properties = $app->response->data['properties']}
    {if $properties}
        {*Вывод свойств*}
        {$pathPiece = $app->response->data['pathPiece']}
        {include file="@commonprj/components/templateEngine/subtemplates/$pathPiece/propertyArea/properties.tpl"}
    {else}
        <h3 class="text-center">Нет свойств</h3>
    {/if}
{/if}
