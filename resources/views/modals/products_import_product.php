<?php
echo BootForm::open()->post()->multipart('form-data')->action(route('products.import', $eventID));
echo BootForm::hidden('_action')->value('import_product');
echo BootForm::file('Select File', 'import_file')->accept("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel");
echo Button::submit()->info()->withValue('Import')->block();
echo BootForm::close();