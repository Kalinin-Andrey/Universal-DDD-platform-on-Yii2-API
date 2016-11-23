{*Подшаблон для вывода инклюзий
Доступные переменные:
$inclusions - массив с массивами инклюзий, сгруппированными по RelationClass и RelationGroup
$inclusions[$relationClassId][$relationGroupId] =
[
    'inclusion' => common\Element[] $inclusion,
    'relation_class' => RelationClass $relationClass
]
*}
<div style="background: #35414f; color: #ffffff; padding-top: 20px; padding-bottom: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {foreach $inclusions as $relationClassId => $relationGroup}
                    {foreach $relationGroup as $relationGroupId => $arItem}
                        <h3 class="text-center">{$arItem['relation_class']->name}</h3>
                        <hr>
                        <div class="row">
                        {foreach $arItem['inclusion'] as $item}
                            {$className = get_class($item)}
                            {$uid = $app->route->params2Uid('enc', $className, $item->id)}
                            {$image = $app->templateEngineHelper->getImageByElement($item)}
                            <div class="col-lg-3">
                                <table>
                                    <tr height="200">
                                        <td width="45%">
                                            <img src="{$image}" alt="" class="img-responsive">
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
                            {foreachelse}
                            <div class="col-xs-12">
                                <h5 class="text-center">У элемента в данной группе нет инклюзий!</h5>
                            </div>
                        {/foreach}
                        </div>
                    {/foreach}
                {/foreach}
            </div>
        </div>
    </div>
</div>



