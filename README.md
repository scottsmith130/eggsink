# EggSink

## What is EggSink?

EggSink is a script for synchronizing data between Microsoft Exchange Server and Google Calendar. It was written because at the time there was a lack of options out there for non-Windows platforms to synchronize calendar data from Exchange to Google Calendar (generally you need to be using Microsoft Outlook on Microsoft Windows in order to use most of the tools available for this, and most of them are not free).

**_NOTE: EggSink currently only does one-way synchronization of events from an Exchange calendar to Google Calendar._**

## Installation
EggSink Uses https://github.com/jamesiarmes/php-ews to talk to Exchange Web Services and https://github.com/google/google-api-php-client to talk to the Google Calendar. Both libraries are included in hte project, but may be setup using Composer in the future. 

EggSink also requires at least PHP 5.4.

Simply download the source for this and setup the configuration.  You will need to have an active Exchange account and enabled Google Calendar API for your existing Google account.

## Configuration
To configure, create a config directory in the root of the project, and place a config.php file under the config directory. The config.php file should have the following settings defined:

```php
const SYNC_DAYS_FROM_NOW = 1; // number of days in the future to sync

const EXCHANGE_SERVER = ''; // the hostname of the Exchange server
const EXCHANGE_USERNAME = ''; // the username for the Exchange server
const EXCHANGE_PASSWORD = ''; // the password for the Exchange server

const GOOGLE_CALENDAR_ID = ''; // the ID of the Google Calendar being synced
const GOOGLE_CLIENT_ID = ''; // Google Calendar API service account client ID
const GOOGLE_EMAIL = ''; // Google Calendar API service account email
const GOOGLE_KEY_FILE = '*.p12'; // Google Calendar API service account p12 file name
```

Make sure the P12 file is also placed into the config directory.

## Usage
To run the script (assuming php is in the path and already in the EggSink project directory), use the command line:
```
php eggsink.php
```

This script is probably most useful when configured to run on a schedule. 

## License

EggSink is free software, and may be redistributed under the MIT License.
