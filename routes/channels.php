<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('update.sales.{eventID}', function ($user, $eventID) {
    return (int) $user->FK_eventID === (int) $eventID;
});

Broadcast::channel('update.error.{eventID}', function ($user, $eventID) {
    return (int) $user->FK_eventID === (int) $eventID;
});
