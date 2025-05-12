<?php
/**
 * Build a responsive <picture> element from a single “base” image path.
 *
 * The file naming rule is:   {base}_{SIZE}.{ext}
 * …where SIZE ∈ 640|1024|1536|2048|2560 and ext ∈ avif|webp|jpg
 *
 * @param string $imagePath  The value from products.json (any size / ext OK)
 * @param string $alt        Alt text
 * @param string $class      Optional CSS class for the <img> fallback
 * @return string            HTML <picture> element
 */
function responsiveImage(string $imagePath, string $alt = '', string $class = ''): string
{
    $info      = pathinfo($imagePath);
    $dir       = ($info['dirname'] !== '.') ? $info['dirname'].'/' : '';
    $filename  = $info['filename'];                     // e.g. “sample-soap_640”  OR “sample-soap”
    $base      = preg_replace('/_\d+$/', '', $filename); // strip “…_640” if present

    $widths = [640, 1024, 1536, 2048, 2560];

    // Build srcset strings
    $buildSet = function (string $ext) use ($dir, $base, $widths) {
        return implode(', ', array_map(
            fn($w) => "{$dir}{$base}_{$w}.{$ext} {$w}w",
            $widths
        ));
    };

    $sizes = '(min-width:1200px) 22vw, (min-width:768px) 45vw, 90vw'; // tweak as you like

    // Fallback JPG at the smallest size
    $fallback = "{$dir}{$base}_640.jpg";

    return <<<HTML
<picture>
    <source type="image/avif" srcset="{$buildSet('avif')}" sizes="$sizes">
    <source type="image/webp" srcset="{$buildSet('webp')}" sizes="$sizes">
    <img src="$fallback" alt="{$alt}" loading="lazy" class="$class">
</picture>
HTML;
}
