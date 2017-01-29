<?php
    echo BootForm::open(['route' => 'event.create']);
    echo BootForm::text('event_name', 'Name', null,
    ['placeholder' => 'eksemple: Vinterfesten 2016', 'rows' => 1]);
    echo BootForm::submit('Save', ['class' => 'btn-block btn-primary btn']);
    echo BootForm::close();
