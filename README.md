# EggSink

Script for synchronizing data between Microsoft Exchange Server and Google Calendar.  Uses Exchange Web Services and Google Calendar API for data exchange.

**_NOTE: Currently only does one-way synchronization of events from an Exchange calendar to Google Calendar._**

Simply download the source for this and setup the configuration.  You will need to have an active Exchange account and enabled Google Calendar API for your existing Google account.

To configure, create a config directory in the root of the project, and place a config.php file under the config directory. The config.php file should have the following settings defined:

```php
const SYNC_DAYS_FROM_NOW = 1; // number of days in the future to sync
const SERVER = ''; // the hostname of the Exchange server
const USERNAME = ''; // the username for the Exchange server
const PASSWORD = ''; // the password for the Exchange server

const GOOGLE_CALENDAR_ID = ''; // the ID of the Google Calendar being synced
const GOOGLE_CLIENT_ID = ''; // Google Calendar API service account client ID
const GOOGLE_EMAIL = ''; // Google Calendar API service account email
const GOOGLE_KEY_FILE = '*.p12'; // Google Calendar API service account p12 file name
```

Make sure the P12 file is also placed into the config directory.

