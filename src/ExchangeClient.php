<?php

class ExchangeClient
{
    private $client = null;

    public function __construct($server, $username, $password)
    {
        $this->client = new ExchangeWebServices($server, $username, $password, ExchangeWebServices::VERSION_2010_SP2);
    }

    /**
     * @param int $daysFromNow
     * @return array
     */
    public function getCalendarEvents($daysFromNow)
    {
        // Set init class
        $request = new EWSType_FindItemType();

        // Use this to search only the items in the parent directory in question or use ::SOFT_DELETED
        // to identify "soft deleted" items, i.e. not visible and not in the trash can.
        $request->Traversal = EWSType_ItemQueryTraversalType::SHALLOW;

        // This identifies the set of properties to return in an item or folder response
        $request->ItemShape = new EWSType_ItemResponseShapeType();
        $request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;

        // Define the timeframe to load calendar items
        $request->CalendarView = new EWSType_CalendarViewType();
        $request->CalendarView->StartDate = date(DATE_RFC3339);
        $request->CalendarView->EndDate = date(DATE_RFC3339, strtotime('+' . $daysFromNow . ' days'));

        // Only look in the "calendars folder"
        $request->ParentFolderIds = new EWSType_NonEmptyArrayOfBaseFolderIdsType();
        $request->ParentFolderIds->DistinguishedFolderId = new EWSType_DistinguishedFolderIdType();
        $request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::CALENDAR;

        // Send request
        $response = $this->client->FindItem($request);

        // Loop through each item if event(s) were found in the timeframe specified
        $items = [];
        if ($response->ResponseMessages->FindItemResponseMessage->RootFolder->TotalItemsInView > 0) {
            $events = $response->ResponseMessages->FindItemResponseMessage->RootFolder->Items->CalendarItem;
            foreach ($events as $event) {
                $items[$event->ItemId->Id] = [
                    'id' => $event->ItemId->Id,
                    'changeKey' => $event->ItemId->ChangeKey,
                    'subject' => $event->Subject,
                    'start' => $event->Start,
                    'end' => $event->End,
                    'location' => !empty($event->Location) ? $event->Location : null,
                    'isAllDayEvent' => !empty($event->IsAllDayEvent) ? true : false,
                    'isPublic' => strtolower($event->Sensitivity) == 'normal' ? true : false,
                    'isBusyStatus' => strtolower($event->LegacyFreeBusyStatus) == 'busy' ? true : false,
                    'isReminderSet' => !empty($event->ReminderIsSet) ? true : false,
                    'reminderMinutesBeforeStart' => !empty($event->ReminderMinutesBeforeStart) ? $event->ReminderMinutesBeforeStart : null
                ];
            }
        }

        return $items;
    }
}