<?php

echo BootForm::open()->post()->multipart('form-data')->action(route('storages.import_sales',$event->id));
echo BootForm::hidden('_action')->value('import_sales');

$storages = $event->storages()->where('depot', false)->orderBy('depot', 'DESC')->get();

$options = $storages->mapWithKeys(function ($item) {
    return [$item['id'] => $item['name']];
});

echo BootForm::select('Storage', 'storageFrom', $options);
echo BootForm::file('Select File', 'import_file')->accept("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel");

echo Button::submit()->withValue('Save')->block();
echo BootForm::close();