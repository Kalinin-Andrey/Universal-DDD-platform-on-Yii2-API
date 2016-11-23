{*Подшаблон для вывода предков
Доступные переменные:
$parents[] - массив с предками первого уровня
$parents = [
    $relationClassId => [
        'relationGroups' => [
            $relationGroupId => Element,
            ...
        ],
        'relationClass' => RelationClass,
    ],
    ...
]
*}
<div style="background: #35414f; color: #ffffff; padding-top: 20px; padding-bottom: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {foreach $parents as $value}
                    <h3 class="text-center">{$value['relationClass']->name}</h3>
                    <hr>
                    <div class="row">
                    {foreach $value['relationGroups'] as $item}
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
                                        <a href="/encyclopedia?UID={$uid}"  style="color:#FFFFFF">{$item->name}</a>
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
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>