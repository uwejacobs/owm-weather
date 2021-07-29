<?php
function generateColorCSS($hexColor, $name) {

    $hsl = hex2hsl($hexColor);
    $rgb = hex2rgb($hexColor);

    $t1 = hsl2hex(array($hsl[0], $hsl[1], $hsl[2]));
    $t2 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2], -0.10)));
    $t3 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2],  0.31)));
    $t4 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2],  0.36)));
    $t5 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2], -0.24)));
    $t6 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2], -0.13)));
    $t7 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2], -0.07)));
    $t8 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2], -0.34)));
    $t9 = hsl2hex(array($hsl[0], $hsl[1], hslAddition($hsl[2],  0.40)));
    $shadow = '0 0 0 0.2rem rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.5)';
    $isDark = isDark($rgb);
    $s = [];
    
    //----------------------------------------
    // t1
    //----------------------------------------
    addStylesheetRule($s, ".badge-".$name, "background-color", $t1);
    addStylesheetRule($s, ".bg-".$name, "background-color", $t1);
    addStylesheetRule($s, ".border-".$name, "border-color", $t1);
    addStylesheetRule($s, ".btn-".$name, "background-color", $t1);
    addStylesheetRule($s, ".btn-".$name, "border-color", $t1);
    addStylesheetRule($s, ".btn-".$name.".disabled, .btn-".$name.":disabled", "background-color", $t1);
    addStylesheetRule($s, ".btn-".$name.".disabled, .btn-".$name.":disabled", "border-color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name, "color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name, "border-color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name.":hover", "background-color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name.":hover", "border-color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name.".disabled, .btn-outline-".$name.":disabled", "color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name.":not(:disabled):not(.disabled):active, .btn-outline-".$name.":not(:disabled):not(.disabled).active, .show > .btn-outline-".$name.".dropdown-toggle", "background-color", $t1);
    addStylesheetRule($s, ".btn-outline-".$name.":not(:disabled):not(.disabled):active, .btn-outline-".$name.":not(:disabled):not(.disabled).active, .show > .btn-outline-".$name.".dropdown-toggle", "border-color", $t1);
    addStylesheetRule($s, ".text-".$name, "color", $t1);


    //----------------------------------------
    // t2
    //----------------------------------------
    addStylesheetRule($s, ".badge-".$name."[href]:hover, .badge-".$name."[href]:focus", "background-color", $t2);
    addStylesheetRule($s, "a.bg-".$name.":hover, a.bg-".$name.":focus, button.bg-".$name.":hover, button.bg-".$name.":focus", "background-color", $t2);
    addStylesheetRule($s, ".btn-".$name.":hover", "border-color", $t2);
    addStylesheetRule($s, ".btn-".$name.":not(:disabled):not(.disabled):active, .btn-".$name.":not(:disabled):not(.disabled).active, .show > .btn-".$name.".dropdown-toggle", "background-color", $t2);
    addStylesheetRule($s, "a.text-".$name.":hover, a.text-".$name.":focus", "color", $t2);


    //----------------------------------------
    // t3
    //----------------------------------------
    addStylesheetRule($s, ".alert-".$name." hr", "border-top-color", $t3);
    addStylesheetRule($s, ".list-group-item-".$name.".list-group-item-action:hover, .list-group-item-".$name.".list-group-item-action:focus", "background-color", $t3);
    addStylesheetRule($s, ".table-hover .table-".$name.":hover", "background-color", $t3);
    addStylesheetRule($s, ".table-hover .table-".$name.":hover > td, .table-hover .table-".$name.":hover > th", "background-color", $t3);


    //----------------------------------------
    // t4
    //----------------------------------------
    addStylesheetRule($s, ".alert-".$name, "border-color", $t4);
    addStylesheetRule($s, ".list-group-item-".$name, "background-color", $t4);
    addStylesheetRule($s, ".table-".$name.", .table-".$name." > th, .table-".$name." > td", "background-color", $t4);


    //----------------------------------------
    // t5
    //----------------------------------------
    addStylesheetRule($s, ".alert-".$name, "color", $t5);
    addStylesheetRule($s, ".list-group-item-".$name, "color", $t5);
    addStylesheetRule($s, ".list-group-item-".$name.".list-group-item-action:hover, .list-group-item-".$name.".list-group-item-action:focus", "color", $t5);
    addStylesheetRule($s, ".list-group-item-".$name.".list-group-item-action.active", "background-color", $t5);
    addStylesheetRule($s, ".list-group-item-".$name.".list-group-item-action.active", "border-color", $t5);

    //----------------------------------------
    // t6
    //----------------------------------------
    addStylesheetRule($s, ".btn-".$name.":not(:disabled):not(.disabled):active, .btn-".$name.":not(:disabled):not(.disabled).active, .show > .btn-".$name.".dropdown-toggle", "border-color", $t6);


    //----------------------------------------
    // t7
    //----------------------------------------
    addStylesheetRule($s, ".btn-".$name.":hover", "background-color", $t7);

    //----------------------------------------
    // t8
    //----------------------------------------
    addStylesheetRule($s, ".alert-".$name." .alert-link", "color", $t8);

    //----------------------------------------
    // t9
    //----------------------------------------
    addStylesheetRule($s, ".alert-".$name, "background-color", $t9);

    //----------------------------------------
    // shadow
    //----------------------------------------
    addStylesheetRule($s, ".btn-".$name.":focus, .btn-".$name.".focus", "box-shadow", $shadow);
    addStylesheetRule($s, ".btn-".$name.":not(:disabled):not(.disabled):active:focus, .btn-".$name.":not(:disabled):not(.disabled).active:focus, .show > .btn-".$name.".dropdown-toggle:focus", "box-shadow", $shadow);
    addStylesheetRule($s, ".btn-outline-".$name.":focus, .btn-outline-".$name.".focus", "box-shadow", $shadow);
    addStylesheetRule($s, ".btn-outline-".$name.":not(:disabled):not(.disabled):active:focus, .btn-outline-".$name.":not(:disabled):not(.disabled).active:focus, .show > .btn-outline-".$name.".dropdown-toggle:focus", "box-shadow", $shadow);


    //----------------------------------------
    // dark/light
    //----------------------------------------
    $textColor = $isDark ? '#212529' : '#fff';
    addStylesheetRule($s, ".badge-".$name, "color", $textColor);
    addStylesheetRule($s, ".badge-".$name."[href]:hover, .badge-".$name."[href]:focus", "color", $textColor);
    addStylesheetRule($s, ".btn-".$name, "color", $textColor);
    addStylesheetRule($s, ".btn-".$name.":hover", "color", $textColor);
    addStylesheetRule($s, ".btn-".$name.".disabled, .btn-".$name.":disabled", "color", $textColor);
    addStylesheetRule($s, ".btn-".$name.":not(:disabled):not(.disabled):active, .btn-".$name.":not(:disabled):not(.disabled).active, .show > .btn-".$name.".dropdown-toggle", "color", $textColor);
    addStylesheetRule($s, ".btn-outline-".$name.":hover", "color", $textColor);
    addStylesheetRule($s, ".btn-outline-".$name.":not(:disabled):not(.disabled):active, .btn-outline-".$name.":not(:disabled):not(.disabled).active, .show > .btn-outline-".$name.".dropdown-toggle", "color", $textColor);
    addStylesheetRule($s, ".list-group-item-".$name.".list-group-item-action.active", "color", $textColor);

    $str = printStylesheetRules($s);

    return $str;
}

function addStylesheetRule(&$s, $selector, $property, $value) {
    $s[$selector][] = $property . ":" . $value . ';';
}

function printStylesheetRules($s) {
    $css = '';
        
    foreach ($s as $key => $arr) {
        $css .= $key . '{';
        foreach ($arr as $value) {
            $css .= $value;
        }
        $css .= '}';
    }
    return $css;
}

/**
 * RGB-to-HSL and HSL-to-RGB Converter
 * Check http://www.michaelburri.ch/generate-different-shades-of-a-color/ for explanation
 * @author     Michael Burri, https://github.com/mpbzh
 * @license    GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */

// Validates hex color code and returns proper value
// Input: String - Format #ffffff, #fff, ffffff or fff
// Output: hex value - 3 byte (000000 if input is invalid)
function validate_hex($hex) {
    // Complete patterns like #ffffff or #fff
    if(preg_match("/^#([0-9a-fA-F]{6})$/", $hex) || preg_match("/^#([0-9a-fA-F]{3})$/", $hex)) {
        // Remove #
        $hex = substr($hex, 1);
    }

    // Complete patterns without # like ffffff or 000000
    if(preg_match("/^([0-9a-fA-F]{6})$/", $hex)) {
        return $hex;
    }

    // Short patterns without # like fff or 000
    if(preg_match("/^([0-9a-f]{3})$/", $hex)) {
        // Spread to 6 digits
        return substr($hex, 0, 1) . substr($hex, 0, 1) . substr($hex, 1, 1) . substr($hex, 1, 1) . substr($hex, 2, 1) . substr($hex, 2, 1);
    }

    // If input value is invalid return black
    return "000000";
}

// Converts hex color code to HSL color
// Input: String - Format #ffffff, #fff, ffffff or fff
// Output: Array(Hue, Saturation, Lightness) - Values from 0 to 1
function hex2hsl($hex) {
    //Validate Hex Input
    $hex = validate_hex($hex);

    // Split input by color
    $hex = str_split($hex, 2);

    // Convert color values to value between 0 and 1
    $r = (hexdec($hex[0])) / 255;
    $g = (hexdec($hex[1])) / 255;
    $b = (hexdec($hex[2])) / 255;

    return rgb2hsl(array($r,$g,$b));
}

// Converts RGB color to HSL color
// Check http://en.wikipedia.org/wiki/HSL_and_HSV#Hue_and_chroma for
// details
// Input: Array(Red, Green, Blue) - Values from 0 to 1
// Output: Array(Hue, Saturation, Lightness) - Values from 0 to 1
function rgb2hsl($rgb) {
    // Fill variables $r, $g, $b by array given.
    list($r, $g, $b) = $rgb;

    // Determine lowest & highest value and chroma
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;

    // Calculate Luminosity
    $l = ($max + $min) / 2;

    // If chroma is 0, the given color is grey
    // therefore hue and saturation are set to 0
    if ($chroma == 0)
    {
        $h = 0;
        $s = 0;
    }

    // Else calculate hue and saturation.
    // Check http://en.wikipedia.org/wiki/HSL_and_HSV for details
    else
    {
        switch($max) {
            case $r:
                $h_ = fmod((($g - $b) / $chroma), 6);
                if($h_ < 0) $h_ = (6 - fmod(abs($h_), 6)); // Bugfix: fmod() returns wrong values for negative numbers
                break;

            case $g:
                $h_ = ($b - $r) / $chroma + 2;
                break;

            case $b:
                $h_ = ($r - $g) / $chroma + 4;
                break;
            default:
                break;
        }

        $h = $h_ / 6;
        $s = 1 - abs(2 * $l - 1);
    }

    // Return HSL Color as array
    return array($h, $s, $l);
}

// Converts HSL color to RGB color
// Input: Array(Hue, Saturation, Lightness) - Values from 0 to 1
// Output: Array(Red, Green, Blue) - Values from 0 to 1
function hsl2rgb($hsl) {
    // Fill variables $h, $s, $l by array given.
    list($h, $s, $l) = $hsl;

    // If saturation is 0, the given color is grey and only
    // lightness is relevant.
    if ($s == 0 ) {
        $rgb = array($l, $l, $l);
    }

    // Else calculate r, g, b according to hue.
    // Check http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL for details
    else
    {
        $chroma = (1 - abs(2*$l - 1)) * $s;
        $h_     = $h * 6;
        $x         = $chroma * (1 - abs((fmod($h_,2)) - 1)); // Note: fmod because % (modulo) returns int value!!
        $m = $l - round($chroma/2, 10); // Bugfix for strange float behaviour (e.g. $l=0.17 and $s=1)

             if($h_ >= 0 && $h_ < 1) $rgb = array(($chroma + $m), ($x + $m), $m);
        else if($h_ >= 1 && $h_ < 2) $rgb = array(($x + $m), ($chroma + $m), $m);
        else if($h_ >= 2 && $h_ < 3) $rgb = array($m, ($chroma + $m), ($x + $m));
        else if($h_ >= 3 && $h_ < 4) $rgb = array($m, ($x + $m), ($chroma + $m));
        else if($h_ >= 4 && $h_ < 5) $rgb = array(($x + $m), $m, ($chroma + $m));
        else if($h_ >= 5 && $h_ < 6) $rgb = array(($chroma + $m), $m, ($x + $m));
    }

    return $rgb;
}

// Converts RGB color to hex code
// Input: Array(Red, Green, Blue)
// Output: String hex value (#000000 - #ffffff)
function rgb2hex($rgb) {
    list($r,$g,$b) = $rgb;
    $r = round(255 * $r);
    $g = round(255 * $g);
    $b = round(255 * $b);
    return "#".sprintf("%02X",$r).sprintf("%02X",$g).sprintf("%02X",$b);
}

// Converts HSL color to RGB hex code
// Input: Array(Hue, Saturation, Lightness) - Values from 0 to 1
// Output: String hex value (#000000 - #ffffff)
function hsl2hex($hsl) {
    $rgb = hsl2rgb($hsl);
    return rgb2hex($rgb);
}

// Converts hex color code to RGB color
// Input: String - Format #ffffff, #fff, ffffff or fff
// Output: Array(Hue, Saturation, Lightness) - Values from 0 to 1
function hex2rgb($hex) {
    //Validate Hex Input
    $hex = validate_hex($hex);

    // Split input by color
    $hex = str_split($hex, 2);

    // Convert hex color values to value between 0 and 255
    $r = hexdec($hex[0]);
    $g = hexdec($hex[1]);
    $b = hexdec($hex[2]);

    return array($r,$g,$b);
}
function hslAddition($hslValue, $number) {
    $newVal = $hslValue + $number;
    if ($newVal < 0) {
        $newVal = 0;
    } else if ($newVal > 1) {
        $newVal = 1;
    }
    return $newVal;
}
function isDark($rgb) {
    $hsp = sqrt(
       0.299 * ($rgb[0] * $rgb[0]) +
       0.587 * ($rgb[1] * $rgb[1]) +
       0.114 * ($rgb[2] * $rgb[2])
       );

    return ($hsp > 127.5);
}