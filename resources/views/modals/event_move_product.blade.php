<?php
echo BootForm::open()->post()->action(route('storages.move_product',$event->id));
echo BootForm::hidden('_action')->value('move_product');

$products = $event->products()->get();
$storages = $event->storages()->orderBy('depot', 'DESC')->get();

$options = $storages->mapWithKeys(function ($item) {
    return [$item['id'] => $item['name']];
});

echo BootForm::select('From', 'from', $options);
echo BootForm::select('To', 'to', $options)->select($storages[1]->id);

$counter = 0;
foreach($products as $product)
{
    echo Bootform::text($product->name, 'products[' . $product->id . ']' )->data('productID', $product->id);
    $counter++;
}
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();