<?php
echo BootForm::open(['route' => ['admin.add_user']]);
echo BootForm::hidden('_action', 'add_user');
echo BootForm::text('username', 'Username');
echo BootForm::text('name', 'Name');
echo BootForm::email('email');
echo BootForm::password();
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();