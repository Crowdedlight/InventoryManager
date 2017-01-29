<?php
echo BootForm::open(['route' => ['event.add_storage', $event->id]]);
echo BootForm::hidden('_action', 'add_storage');
echo BootForm::text('storageName', 'Storage Name');
?> <small class="text-muted">Enter Storage Name</small> <?php
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();