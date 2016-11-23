{*Подшаблон для вывода свойств
Доступные переменные:
$properties - массив
$properties = [
    [
        'property' => Property, //объект конкретного свойства
        'value' => mixed, //значение данного свойства у тукущего элемента
        'unit' => string, //единица измерения данного свойства, пустая строка, если не установлена
        'allValues' => [ //возможные значения данного свойства
            'propertyValuesByMultiplicityId' => [
                1 => [
                    AbstractPropertyValue[], //одиночные значения
                ],
                2 => [
                    AbstractPropertyValue[], //диапо зон значений
                ],
                3 => [
                    AbstractPropertyValue[], //несколько значений
                ],
            ],
        ],
    ],
    ...
]
*}
<h3 class="text-center">Cвойства:</h3>

{foreach $properties as $property}
    <hr>
    <h5 class="text-uppercase">{$property@iteration}. {$property['property']->name}</h5>

    {foreach $property['allValues']['propertyValuesByMultiplicityId'] as $multId => $values}

        {foreach $values as $value}
            {if $value['id'] !== $property['value']->id}
                <a href="/filter-by-properties?{$value['filterByProperties']}" style="display:inline-block">
            {/if}
            <span style="display:inline-block;padding:5px 7px;
            {if $value['id'] === $property['value']->id}
                    background:#2e6da4;color:#FFFFFF;
            {else}
                    background:#FFFFFF;color:#1a1a1a;
            {/if}
                    margin:0 5px 10px;">
                {if $multId === 1}
                    {$value['value']}{$property['unit']}
                {elseif $multId === 2}
                    от {$value['fromValue']}{$property['unit']} до {$value['toValue']}{$property['unit']}
                {elseif $multId === 3}
                    {foreach $value['values'] as $item}
                        {$item}{$property['unit']}{if !$item@last} | {/if}
                    {/foreach}
                {/if}
                </span>
            {if $value['id'] !== $property['value']->id}
                </a>
            {/if}
        {/foreach}
    {/foreach}
{/foreach}

