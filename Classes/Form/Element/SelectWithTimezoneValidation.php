<?php

namespace TYPO3Incubator\SurfcampEvents\Form\Element;

use TYPO3\CMS\Backend\Form\InlineStackProcessor;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Location;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\LocationRepository;

class SelectWithTimezoneValidation extends \TYPO3\CMS\Backend\Form\Element\SelectSingleElement
{
    public function __construct(
        InlineStackProcessor $inlineStackProcessor,
        private LocationRepository $locationRepository
    ) {
        parent::__construct($inlineStackProcessor);
    }

    /**
     * This will render a selector box element, or possibly a special construction with two selector boxes.
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render(): array
    {
        $resultArray = parent::render();

        if (!array_key_exists('location', $this->data['databaseRow'])
            || count($this->data['databaseRow']['location']) === 0) {
            return $resultArray;
        }

        $locationId = $this->data['databaseRow']['location'][0];
        /** @var Location $location */
        $location = $this->locationRepository->findByUid($locationId);
        if (!$location || !$location->getTimezone()) {
            return $resultArray;
        }
        $locationTimezone = $location->getTimezone();

        if (!array_key_exists('timezone', $this->data['databaseRow'])) {
            return $resultArray;
        }
        $selectedTimezone = $this->data['databaseRow']['timezone'][0];

        if ($selectedTimezone === $locationTimezone) {
            return $resultArray;
        }

        $message = GeneralUtility::makeInstance(FlashMessage::class,
            "The selected timezone doesn't match the timezone from the location. Don't worry. Despite this the object can be saved.",
            'Double-check your selected timezone',
            ContextualFeedbackSeverity::WARNING,
            true
        );

        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);

        return $resultArray;
    }
}
