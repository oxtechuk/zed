<?php

$im = imagecreatefrompng('C:/Users/Ox Tech/.gemini/antigravity-ide/brain/73b0e5c3-ea62-492a-8ea3-8954b5d5795d/.user_uploaded/media_1790429231432.png');
$w = imagesx($im);
$h = imagesy($im);
echo "Image Size: {$w} x {$h}\n";

$bx = [];
$by = [];
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($im, $x, $y);
        $b = $c & 0xFF;
        $g = ($c >> 8) & 0xFF;
        $r = ($c >> 16) & 0xFF;
        if ($b > 180 && $r < 60 && $g < 100) {
            $bx[] = $x;
            $by[] = $y;
        }
    }
}
if (! empty($bx)) {
    echo 'Blue circle: X = '.min($bx).'..'.max($bx).', Y = '.min($by).'..'.max($by)."\n";
    $cx = (min($bx) + max($bx)) / 2;
    $cy = (min($by) + max($by)) / 2;
    echo "Center: X = $cx, Y = $cy\n";
    echo 'Relative X: '.round($cx / $w * 100).'%, Relative Y: '.round($cy / $h * 100)."%\n";
}
