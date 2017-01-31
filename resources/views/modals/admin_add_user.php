<?php
echo BootForm::open()->post()->action(route('admin.add_user'));
echo BootForm::hidden('_action')->value('add_user');
echo BootForm::text('Username', 'username');
echo BootForm::text('Name', 'name');
echo BootForm::email('Email', 'email');
echo BootForm::password('Password', 'password');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();