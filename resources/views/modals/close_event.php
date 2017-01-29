<?php
echo BootForm::open(['route' => ['event.close', $event->id]]);
echo BootForm::hidden('_action', 'close');
echo BootForm::textarea('comment');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();