<?php
    echo BootForm::open()->post()->action(route('event.create'));
    echo BootForm::text('Name', 'event_name')->placeholder('eksemple: Vinterfesten 2016');
    echo BootForm::submit('Save')->addClass('btn-block btn-primary btn');
    echo BootForm::close();
