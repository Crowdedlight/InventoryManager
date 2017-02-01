<?php
$columnSizes = [
        'sm' => [4, 8],
        'lg' => [2, 10]
];

echo BootForm::openHorizontal($columnSizes)->post()->action(route('storages.update_sales',$event->id));
echo BootForm::hidden('_action')->value('update_sales');

$products = $event->products()->get();
$storages = $event->storages()->where('depot', false)->orderBy('depot', 'DESC')->get();

$options = $storages->mapWithKeys(function ($item) {
    return [$item['id'] => $item['name']];
});

echo BootForm::select('Storage', 'storageFrom', $options);

$counter = 0;
foreach($products as $product)
{
    echo Bootform::text($product->name, 'salesProducts[' . $product->id . ']' )->data('productID', $product->id);
    $counter++;
}
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();