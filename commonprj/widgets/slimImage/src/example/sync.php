<?php

require_once('slim.php');

// Get posted data
$images = Slim::getImages();

// If failed to receive images Slim was not used
if ($images == false) {

    // inject your own auto crop or fallback script here
    echo '<p>Slim was not used to upload these images.</p>';

}
else {

    // Could be multiple slim croppers
    foreach ($images as $image) {

    $file = Slim::saveFile($image['output']['data'], $image['input']['name']);

    echo '
    <h1>You uploaded "' . ellipsis($image['input']['name'],100) . '"</h1>
    
    <img src="' . $file['path'] . '" alt=""/>
    
    <div class="table-wrapper">   
    <table>
        <thead>
            <tr>
                <th>Property</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="2">Saved file</th>
            </tr>
            <tr>
                <th>Name</th>
                <td>' . ellipsis($file['name'],100) . '</td>
            </tr>
            <tr>
                <th>Path</th>
                <td>' . ellipsis($file['path'],100) . '</td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th colspan="2">Original image</th>
            </tr>
            <tr>
                <th>Name</th>
                <td>' . ellipsis($image['input']['name'],100) . '</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>' . $image['input']['type'] . '</td>
            </tr>
            <tr>
                <th>Size</th>
                <td>' . $image['input']['size'] . '</td>
            </tr>
            <tr>
                <th>Width</th>
                <td>' . $image['input']['width'] . '</td>
            </tr>
            <tr>
                <th>Height</th>
                <td>' . $image['input']['height'] . '</td>
            </tr>
            <tr>
                <th>Data</th>
                '
                .
                (isset($image['input']['data']) ?
                    '<td class="bytes">' . ellipsis(base64_encode($image['input']['data']), 100) . '</td>' :
                    '<td>Input data was not sent, add the "input" value to the <code>data-post</code> property to send it along.</td>')
                .
                '
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th colspan="2">Output image</th>            
            </tr>
            <tr>
                <th>Width</th>
                <td>' . $image['output']['width'] . '</td>
            </tr>
            <tr>
                <th>Height</th>
                <td>' . $image['output']['height'] . '</td>
            </tr>
            <tr>
                <th>Data</th>
                '
                .
                (isset($image['output']['data']) ?
                    '<td class="bytes">' . ellipsis(base64_encode($image['output']['data']), 100) . '</td>' :
                    '<td>Output data was not sent, add the "output" value to the <code>data-post</code> property to send it along.</td>')
                .
                '
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th colspan="2">Crop</th>
            </tr>
            <tr>
                <th>Type</th>
                <td>' . $image['actions']['crop']['type'] . '</td>
            </tr>
            <tr>
                <th>X</th>
                <td>' . $image['actions']['crop']['x'] . '</td>
            </tr>
            <tr>
                <th>Y</th>
                <td>' . $image['actions']['crop']['y'] . '</td>
            </tr>
            <tr>
                <th>Width</th>
                <td>' . $image['actions']['crop']['width'] . '</td>
            </tr>
            <tr>
                <th>Height</th>
                <td>' . $image['actions']['crop']['height'] . '</td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th colspan="2">Size</th>            
            </tr>
            <tr>
                <th>Width</th>
                <td>' . $image['actions']['size']['width'] . '</td>
            </tr>
            <tr>
                <th>Height</th>
                <td>' . $image['actions']['size']['height'] . '</td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th colspan="2">Meta</th>            
            </tr>
            <tr>
                <th>Data</th>
                <td>' . ($image['meta'] ? json_encode($image['meta']) : 'No meta data received') . '</td>
            </tr>
        </tbody>
    </table>
    </div>';

    }
}

function ellipsis($str, $len = 50) {
    return strlen($str) > $len ? substr($str, 0, $len) . '...' : $str;
}