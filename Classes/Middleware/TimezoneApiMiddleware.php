<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampEvents\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Appointment;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\AppointmentRepository;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\EventRepository;
use TYPO3Incubator\SurfcampEvents\Service\EventDatetimeService;
use TYPO3Incubator\SurfcampEvents\Service\TimezoneService;

class TimezoneApiMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TimezoneService $timezoneService,
        private EventRepository $eventRepository,
        private AppointmentRepository $appointmentRepository,
        private EventDatetimeService $eventDatetimeService
    ) {}

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path !== '/api/surfcamp-events/get-time-for-events'
            && $path !== '/api/surfcamp-events/get-time-for-appointments') {
            return $handler->handle($request);
        }

        if (!array_key_exists('ids', $request->getQueryParams())) {
            return (new Response())->withStatus(400);
        }
        $ids = explode(',', $request->getQueryParams()['ids']);

        if (array_key_exists('timezone', $request->getQueryParams())) {
            $timezone = $request->getQueryParams()['timezone'];
        } else {
            $timezone = $this->timezoneService->getUserTimezone();
        }

        switch ($path) {
            case '/api/surfcamp-events/get-time-for-events':
                $data = $this->processEvents($ids, $timezone);
                break;
            case '/api/surfcamp-events/get-time-for-appointments':
                $data = $this->processAppointments($ids, $timezone);
                break;
            default:
                $data = [];
        }

        $body = new Stream('php://temp', 'rw');
        $body->write(json_encode($data));

        return (new Response())
            ->withHeader('content-type', 'application/json; charset=utf-8')
            ->withBody($body)
            ->withStatus(200);
    }

    public function processEvents(array $eventIds, string $timezone): array
    {
        $query = $this->eventRepository->createQuery();
        $events = $query
            ->matching($query->in('uid', $eventIds))
            ->execute();

        $data = [];
        /** @var Event $event */
        foreach ($events as $event) {
            $data[$event->getUid()] = [
                'eventId' => $event->getUid(),
                'startDate' => $this->eventDatetimeService->getEventStartDateInTimezone($event, $timezone),
                'endDate' => $this->eventDatetimeService->getEventEndDateInTimezone($event, $timezone),
                'timezone' => $timezone
            ];
        }

        return $data;
    }

    public function processAppointments(array $eventIds, string $timezone): array
    {
        $query = $this->appointmentRepository->createQuery();
        $appointments = $query
            ->matching($query->in('uid', $eventIds))
            ->execute();

        $data = [];
        /** @var Appointment $appointment */
        foreach ($appointments as $appointment) {
            $data[$appointment->getUid()] = [
                'eventId' => $appointment->getUid(),
                'startDate' => $this->eventDatetimeService->getAppointmentStartDateInTimezone($appointment, $timezone),
                'endDate' => $this->eventDatetimeService->getAppointmentEndDateInTimezone($appointment, $timezone),
                'timezone' => $timezone
            ];
        }

        return $data;
    }
}
