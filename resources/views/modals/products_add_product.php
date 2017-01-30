<?php
echo BootForm::open()->post()->action(route('products.add', $eventID));
echo BootForm::hidden('add_product', '_action');
echo BootForm::text('Product Name', 'name');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();