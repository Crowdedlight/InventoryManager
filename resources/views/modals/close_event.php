<?php
echo BootForm::open()->post()->action(route('event.close', $event->id));
echo BootForm::hidden('close', '_action');
echo BootForm::textarea('comment');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();