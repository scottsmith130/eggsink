<?php

require_once dirname(__FILE__) . '/autoload.php';
require_once dirname(__FILE__) . '/config/config.php';

addToLog('Started eggsink');
for ($i=1; $i<=5; $i++) {
    try
    {
        $exchange = new ExchangeClient(EXCHANGE_SERVER, EXCHANGE_USERNAME, EXCHANGE_PASSWORD);
        $meetings = $exchange->getCalendarEvents(SYNC_DAYS_FROM_NOW);
        break;
    } catch (Exception $e) {
        if ($i==5)
        {
            addToLog('Failed to connect to Exchange. Please try again later.');
            exit(0);
        }
    }
    addToLog('Exchange connection retry ' . $i);
}

$google = new GoogleCalendarClient(GOOGLE_CLIENT_ID, GOOGLE_EMAIL, dirname(__FILE__) . '/config/' . GOOGLE_KEY_FILE, 'EggSink', GOOGLE_CALENDAR_ID);
$events = $google->getEvents(SYNC_DAYS_FROM_NOW);

// Reduce existing Google events to those that were previously exported from Exchange
$ewsEvents = [];
foreach ($events as $event) {
    if (!empty($event['ewsId'])) {
        $ewsEvents[$event['ewsId']] = $event;
    }
}

// Add or update events
foreach ($meetings as $meeting) {
    if (!$meeting['isBusyStatus'] || !$meeting['isPublic']) {
        continue;
    }

    $details = [
        'subject' => $meeting['subject'],
        'location' => $meeting['location'],
        'start' => $meeting['start'],
        'end' => $meeting['end'],
        'isAllDayEvent' => $meeting['isAllDayEvent'],
        'ewsId' => $meeting['id'],
        'ewsChangeKey' => $meeting['changeKey']
    ];


    if (!empty($ewsEvents[$meeting['id']])) { // This an existing meeting

        if ($ewsEvents[$meeting['id']]['ewsChangeKey'] != $meeting['changeKey']) {
            $google->updateEvent($ewsEvents[$meeting['id']]['id'], $details);
        }
        unset($ewsEvents[$meeting['id']]); // remove the event from the remaining events queue

    } else { // This must be a new meeting

        $event = $google->addEvent($details);

    }

}

// Delete remaining events from Google
foreach ($ewsEvents as $event) {
    $google->deleteEvent($event['id']);
}

addToLog('Finished eggsink');

function addToLog($message)
{
    echo '[' . date(DATE_RFC3339) . '] ' . $message . "\n";
}