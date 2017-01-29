<?php
echo BootForm::open(['route' => ['product.add', 'eventID' => $eventID]]);
echo BootForm::hidden('_action', 'add_product');
echo BootForm::text('name', 'Name');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();