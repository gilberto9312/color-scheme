<?php

$param1 = $argv[1];
$param2 = $argv[2];

function hexadecimalToRGB($hexColor)
{
    // Remove the '#' symbol from the hexadecimal color
    $hexColor = ltrim($hexColor, '#');

    // Get the values of the color components (red, green, and blue)
    $R = hexdec(substr($hexColor, 0, 2));
    $G = hexdec(substr($hexColor, 2, 2));
    $B = hexdec(substr($hexColor, 4, 2));

    // Return an array with the RGB values
    return ['R' => $R, 'G' => $G, 'B' => $B];
}

function evaluateCombination($color1, $color2)
{
    // Calculate the difference between the color components of the two colors
    $diffR = abs($color1['R'] - $color2['R']);
    $diffG = abs($color1['G'] - $color2['G']);
    $diffB = abs($color1['B'] - $color2['B']);

    // Define the difference threshold allowed (adjustable according to your preferences)
    $umbral = 50;
    // Sum of color differences
    $totalDiff = $diffR + $diffG + $diffB;

    // Maximum possible value for the sum of differences
    $maxDiff = 255 * 3;

    // Maximum possible value for the sum of differences
    $calidad = 100 - (($totalDiff / $maxDiff) * 100);
    
    
    // Check if the difference between the colors meets the set threshold
    if ($diffR > $umbral || $diffG > $umbral || $diffB > $umbral) {
        $combinacion = false; // Colors don't match
    }else {
        $combinacion = true; // Colors match
    }

    return [$combinacion, $calidad];
}



function calculateSaturation($R, $G, $B)
{
    $max = max($R, $G, $B);
    $min = min($R, $G, $B);

    if ($max === 0) {
        return 0;
    }

    return ($max - $min) / $max;
}

function calculateIntensity($R, $G, $B)
{
    return ($R + $G + $B) / 3;
}

function findBetterCombination($color1, $color2)
{
    // Get the RGB values of the colors
    $R1 = $color1['R'];
    $G1 = $color1['G'];
    $B1 = $color1['B'];

    $R2 = $color2['R'];
    $G2 = $color2['G'];
    $B2 = $color2['B'];

    // Calculate the saturation and intensity of each color
    $saturacion1 = calculateSaturation($R1, $G1, $B1);
    $intensidad1 = calculateIntensity($R1, $G1, $B1);

    $saturacion2 = calculateSaturation($R2, $G2, $B2);
    $intensidad2 = calculateIntensity($R2, $G2, $B2);

    // Calculate the saturation and intensity of each color
    if ($saturacion1 > $saturacion2 || ($saturacion1 === $saturacion2 && $intensidad1 > $intensidad2)) {
        return rgbToHex($color1['R'], $color1['G'], $color1['B']);
    } else {
        return rgbToHex($color2['R'], $color2['G'], $color2['B']);
    }
}

function rgbToHex($red, $green, $blue) {
    $red = validateColorValue($red);
    $green = validateColorValue($green);
    $blue = validateColorValue($blue);
    
    $hex = "#";
    $hex .= str_pad(dechex($red), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($green), 2, "0", STR_PAD_LEFT);
    $hex .= str_pad(dechex($blue), 2, "0", STR_PAD_LEFT);
    
    return $hex;
}

function validateColorValue($value) {
    if ($value < 0) {
        return 0;
    }
    
    if ($value > 255) {
        return 255;
    }
    
    return $value;
}



$color1 = hexadecimalToRGB($param1); 
$color2 = hexadecimalToRGB($param2); 

list($combinacion, $calidad) = evaluateCombination($color1, $color2);
// Check the color scheme
if ($combinacion) {
    echo "Los colores combinan entre sí.\n";
} else {
    echo "Los colores no combinan entre sí.\n";
}
echo "calidad de la combinacion $calidad.\n";
// Find the best combination
$mejorCombinacion = findBetterCombination($color1, $color2);

echo "mejor color para combinar $mejorCombinacion.\n"
?>
