<?php

namespace TYPO3Incubator\SurfcampEvents\Service;

use Cassandra\Date;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Location;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\EventRepository;
use TYPO3Incubator\SurfcampEvents\Service\EventDatetimeService;


final class GoogleCalendarService
{
    public function __construct(
        protected EventRepository $eventsRepository,
        private EventDatetimeService $eventDatetimeService
    )
    {}

    /**
     * Get the Google Calendar URL
     */
    public function getGoogleCalendarUrl(Event $event): string
    {
        $eventLocation = $event->getLocation();
        $googleMapsUrl = $this->getGoogleMapsUrl($eventLocation);

         $startDateTime = $this->eventDatetimeService->getEventStartDateInTimezone($event, $event->getTimezone());
 
         $endDateTime = $this->eventDatetimeService->getEventEndDateInTimezone($event,  $event->getTimezone());
         
         // Convert local time to the desired format for Google Calendar (Ymd\THis)
         $startFormatted = $this->formatGoogleCalendarDate($startDateTime);
         $endFormatted = $this->formatGoogleCalendarDate($endDateTime);
         
         // URL encode the event details and location
         $eventTitle = urlencode($event->getTitle());
         $eventDescription = urlencode($event->getDescription());
         
         // Build the Google Calendar URL
         $googleCalendarUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text={$eventTitle}"
             . "&dates={$startFormatted}/{$endFormatted}"
             . "&details={$eventDescription}"
             . "&location={$googleMapsUrl}";

        return $googleCalendarUrl;
    }

    /**
     * Format the date into Google Calendar format (Ymd\THis)
     */
    private function formatGoogleCalendarDate(string $dateTimeString): string
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $dateTimeString);

        // Return the formatted date in the format Google Calendar expects (Ymd\THis)
        return $dateTime->format('Ymd\THis');
    }

    /**
     * Get Google Maps URL for the location using latitude and longitude
     *
     * @param Location $location
     * @return string
     */
    private function getGoogleMapsUrl(Location $location)
    {
        // If latitude and longitude are available, use them
        if ($location->getLatitude() && $location->getLongitude()) {
            return $this->getGoogleMapsUrlFromCoordinates($location);
        }

        return $this->getGoogleMapsUrlFromAddress($location);
    }

     /**
     * Get Google Maps URL for the location using latitude and longitude
     *
     * @param Location $location
     * @return string
     */
    private function getGoogleMapsUrlFromCoordinates(Location $location)
    {
        $latitude = $location->getLatitude();
        $longitude = $location->getLongitude();

        // Check if latitude and longitude are valid
        if (!$latitude || !$longitude) {
            return '';
        }

        // Construct the Google Maps URL with coordinates
        return "https://www.google.com/maps?q={$latitude},{$longitude}";
    }

    /**
     * Get Google Maps URL for the location using address
     *
     * @param Location $location
     * @return string
     */
    private function getGoogleMapsUrlFromAddress(Location $location)
    {
        $address = $location->getStreet() . ' ' . $location->getStreetNr() . ', ' .
                  $location->getPostalCode() . ' ' . $location->getCity() . ', ' . 
                  $location->getCountry();

        // URL encode the address
        $encodedAddress = urlencode($address);

        // Construct the Google Maps URL for the address
        return "https://www.google.com/maps/search/?q={$encodedAddress}";
    }
}
