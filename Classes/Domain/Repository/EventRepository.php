<?php

namespace TYPO3Incubator\SurfcampEvents\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class EventRepository extends Repository
{
    /**
     * Default Ordering by UID
     * @var array
     */
    protected $defaultOrderings = ['start_date_time' => QueryInterface::ORDER_DESCENDING];

    public function initializeObject(): void
    {
        /** @var Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findUpcomingEvents(): QueryResultInterface
    {
        $query = $this->createQuery();
        $now = new \DateTimeImmutable('today');

        $query->matching(
            $query->greaterThanOrEqual('end_date_time', $now)
        );

        return $query->execute();
    }
    /**
     * @param string $searchTerm
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findAllWithSearchTerm(string $searchTerm): QueryResultInterface
    {
        if ($searchTerm !== '') {
            $query = $this->createQuery();
            $query->matching(
                $query->logicalOr(
                    $query->like('title', '%' . $searchTerm . '%'),
                )
            );
            return $query->execute();
        } else {
            return $this->findAll();
        }
    }

    public function findByIsOpenForRegistrations(): QueryResultInterface
    {
        try {
            $query = $this->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);
            $query->matching($query->equals('isOpenForRegistrations', true));
            $result = $query->execute();
        } catch (\Exception $e) {
            $result = null;
        }
        return $result;
    }
}
