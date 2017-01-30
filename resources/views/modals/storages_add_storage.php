<?php
echo BootForm::open()->post()->action(route('storages.add', $eventID));
echo BootForm::hidden('add_storage', '_action');
echo BootForm::text('Storage Name', 'name');
echo BootForm::checkbox('Is Depot', 'depot');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();