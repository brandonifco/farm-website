<?php
require_once __DIR__ . '/products.php';

function previewCsv(string $csvPath, array $current): array
{
    $previewAdds = $previewUpdates = [];
    if (($h = fopen($csvPath, 'r')) !== false) {
        fgetcsv($h); // header
        while (($row = fgetcsv($h)) !== false) {
            [$title,$price,$image,$description] = array_map('trim',$row);
            $found = false;
            foreach ($current as $p) {
                if (
                    strcasecmp($p['title'],$title)===0 &&
                    strcasecmp($p['description'],$description)===0
                ) {
                    if ((float)$price !== (float)$p['price'] || $image !== $p['image']) {
                        $previewUpdates[] = [
                            'title'=>$title,
                            'old_price'=>$p['price'],'new_price'=>(float)$price,
                            'old_image'=>$p['image'],'new_image'=>$image
                        ];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $previewAdds[] = compact('title','price','image','description');
            }
        }
        fclose($h);
    }
    return [$previewAdds,$previewUpdates];
}

function importCsv(string $csvPath, array $current): array
{
    [$adds,$updates] = [0,0];
    if (($h = fopen($csvPath,'r'))!==false) {
        fgetcsv($h);
        while(($row=fgetcsv($h))!==false){
            [$title,$price,$image,$description]=array_map('trim',$row);
            $found=false;
            foreach($current as &$p){
                if (
                    strcasecmp($p['title'],$title)===0 &&
                    strcasecmp($p['description'],$description)===0
                ){
                    $p['price']=(float)$price;
                    $p['image']=$image;
                    $updates++; $found=true; break;
                }
            }
            if(!$found){
                $current[]=[
                    'id'=>generateId(),
                    'title'=>$title,
                    'price'=>(float)$price,
                    'image'=>$image,
                    'description'=>$description
                ];
                $adds++;
            }
        }
        fclose($h);
    }
    saveProducts($current);
    return [$adds,$updates];
}
