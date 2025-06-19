<?php

namespace TYPO3Incubator\SurfcampEvents\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Appointment;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;

class RegistrationRepository extends Repository
{
    /**
     * Default Ordering by UID
     * @var array
     */
    protected $defaultOrderings = ['uid' => QueryInterface::ORDER_ASCENDING];

    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findAttendeeCountByEvent(Event $event): int
    {
        try {
            $query = $this->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);
            $query->matching($query->equals('event', $event->getUid()));
            $result = $query->execute();
            return count($result);
        } catch (\Exception $e) {
            return -1;
        }
    }

    public function findAttendeeCountByAppointment(Appointment $appointment): int
    {
        try {
            $query = $this->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);
            $query->matching($query->equals('appointment', $appointment->getUid()));
            $result = $query->execute();
            return count($result);
        } catch (\Exception) {
            return -1;
        }
    }

    public function findEmailAlreadyRegisteredByEvent(Event $event, string $email): bool
    {
        try {
            $query = $this->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);
            $query->matching($query->logicalAnd(
                $query->equals('event', $event->getUid()),
                $query->equals('email', $email)
            ));
            $result = $query->execute();
            return count($result) > 0;
        } catch (\Exception $e) {
            return true;
        }
    }

    public function findEmailAlreadyRegisteredByAppointment(Appointment $appointment, string $email): bool
    {
        try {
            $query = $this->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);
            $query->matching($query->logicalAnd(
                $query->equals('appointment', $appointment->getUid()),
                $query->equals('email', $email)
            ));
            $result = $query->execute();
            return count($result) > 0;
        } catch (\Exception $e) {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($e->getMessage());
            die();
            return true;
        }
    }
}
