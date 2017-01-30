<?php
echo BootForm::open(['route' => ['storage.add', 'eventID' => $eventID]]);
echo BootForm::hidden('_action', 'add_storage');
echo BootForm::text('name', 'Storage Name');
echo BootForm::checkbox('depot', 'Is Depot');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();