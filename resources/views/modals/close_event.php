<?php
echo BootForm::open()->post()->action(route('event.close', $event->id));
echo BootForm::hidden('_action')->value('close');
echo BootForm::textarea('comment');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();