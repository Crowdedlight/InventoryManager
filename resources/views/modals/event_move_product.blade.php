<?php
$columnSizes = [
        'sm' => [4, 8],
        'lg' => [2, 10]
];

echo BootForm::openHorizontal($columnSizes)->post()->action(route('storages.move_product',$event->id));
echo BootForm::hidden('_action')->value('move_product');

$products = $event->products()->get();
$storages = $event->storages()->orderBy('depot', 'DESC')->get();

$options = $storages->mapWithKeys(function ($item) {
    return [$item['id'] => $item['name']];
});

$storageToDefault = $storages->where('depot', false)->first();

echo BootForm::select('From', 'storageFrom', $options);

if($storageToDefault != null)
    echo BootForm::select('To', 'storageTo', $options)->select($storageToDefault->id);
else
    echo BootForm::select('To', 'storageTo', $options);

$counter = 0;
foreach($products as $product)
{
    echo Bootform::text($product->name, 'moveProducts[' . $product->id . ']' )->data('productID', $product->id);
    $counter++;
}
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();