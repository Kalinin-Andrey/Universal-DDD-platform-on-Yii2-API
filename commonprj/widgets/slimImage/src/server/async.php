<?php

// Uncomment if you want to allow posts from other domains
// header('Access-Control-Allow-Origin: *');

require_once('slim.php');

// Get posted data, if something is wrong, exit
try {
    $images = Slim::getImages();
}
catch (Exception $e) {

    // echo error
    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'Unknown'
    ));

    return;
}

// No image found under given input name
if ($images === false) {

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'No data posted'
    ));

    return;
}

// Should always be one dataset (when posting async)
$image = array_shift($images);

// If no images found
if (!isset($image)) {

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'No images found'
    ));

    return;
}

// If image found but no output image data present
if (!isset($image['output']['data'])) {

    Slim::outputJSON(array(
        'status' => SlimStatus::FAILURE,
        'message' => 'No image data'
    ));

    return;
}

// Save the file
$file = Slim::saveFile($image['output']['data'], $image['input']['name']);

// Return results as JSON
Slim::outputJSON(array(
    'status' => SlimStatus::SUCCESS,
    'file' => $file['name'],
    'path' => $file['path']
));