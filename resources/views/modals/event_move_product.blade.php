<?php
echo BootForm::open()->post()->action(route('storages.move_product',$event->id));
echo BootForm::hidden('move_product', '_action');

$products = $event->products()->get();
$storages = $event->storages()->orderBy('depot', 'DESC')->get();

$options = $storages->mapWithKeys(function ($item) {
    return [$item['id'] => $item['name']];
});

echo BootForm::select('Storage', 'storage', $options);

$counter = 0;
foreach($products as $product)
{
    echo Bootform::text('product[' . $counter . ']' , $product->name )->data('productID', $product->id);
    $counter++;
}
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();