<?php
echo BootForm::open(['route' => ['round.add_comment', $round->id]]);
echo BootForm::hidden('_action', 'add_comment');
echo BootForm::textarea('comment');
echo Button::submit()->withValue('Save')->block();
echo BootForm::close();