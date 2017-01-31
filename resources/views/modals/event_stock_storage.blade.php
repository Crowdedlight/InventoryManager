<?php
echo BootForm::open()->post()->action(route('storages.stock_storage',$event->id));
echo BootForm::hidden('_action')->value('stock_storage');

$products = $event->products()->get();
$storages = $event->storages()->orderBy('depot', 'DESC')->get();

$options = $storages->mapWithKeys(function ($item) {
    return [$item['id'] => $item['name']];
});

echo BootForm::select('Storage', 'storage', $options);

$counter = 0;
foreach($products as $product)
{
    echo Bootform::text($product->name, 'products[' . $product->id . ']' )->data('productID', $product->id);
    $counter++;
}
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();