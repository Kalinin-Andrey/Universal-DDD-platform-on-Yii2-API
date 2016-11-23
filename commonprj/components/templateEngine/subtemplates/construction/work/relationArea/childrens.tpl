{*Подшаблон для вывода потомков
Доступные переменные:
$childrens[] - массив с потомками первого уровня
$childrens = [
    $relationClassId => [
        'relationGroups' => [
            $relationGroupId => Element[],
            ...
        ],
        'relationClass' => RelationClass,
    ],
    ...
]
*}
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-center">Используются</h2>
            <br>
            {foreach $childrens as $value}
                <h3 class="text-center">{$value['relationClass']->name}</h3>
                <hr>
                <div class="row">
                {foreach $value['relationGroups'] as $relationGroup}
                    {foreach $relationGroup as $item}
                        {$className = get_class($item)}
                        {$uid = $app->route->params2Uid('enc', $className, $item->id)}
                        {$images = $app->templateEngineHelper->getImageByElement($item)}
                        <div class="col-lg-3">
                            <table>
                                <tr height="200">
                                    <td width="45%">
                                        <img src="{$images}" alt="" class="img-responsive">
                                    </td>
                                    <td width="10%" style="padding-left: 15px">
                                        ->
                                    </td>
                                    <td width="45%" style="vertical-align: middle">
                                        <a href="/encyclopedia?UID={$uid}">{$item->name}</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        {if $item@iteration % 4 == 0}
                            </div>
                            <hr>
                            <div class="row">
                        {/if}
                    {/foreach}
                {/foreach}
                </div>
            {/foreach}
        </div>
    </div>
</div>
