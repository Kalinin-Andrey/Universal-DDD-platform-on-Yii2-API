{*Шаблон-вывод элементов в который входит данный элемент
Доступные переменные:
Element $entity
array $includedIn
*}
<hr>
<div class="container">
    <h3>{$entity->name} входит в состав:</h3>
    <div class="row">
        {foreach $includedIn as $key => $item}
        {$className = get_class($item)}
        {$uid = $app->route->params2Uid('enc', $className, $item->id)}
        <div class="col-lg-3">
            <table>
                <tr height="200">
                    <td width="45%">
                        <img src="{$images[$key]}" alt="" class="img-responsive">
                    </td>
                    <td width="10%" style="padding-left: 15px">
                        ->
                    </td>
                    <td width="45%" style="vertical-align: middle">
                        <a href="/encyclopedia?UID={$uid}"  style="color:#000000">{$item->name}</a>
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
</div>