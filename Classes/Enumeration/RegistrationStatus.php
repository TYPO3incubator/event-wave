<?php

namespace TYPO3Incubator\SurfcampEvents\Enumeration;

final class RegistrationStatus
{
   public const int STATUS_SUCCESSFUL = 0;
   public const int STATUS_INVALID_EMAIL = -1;
   public const int STATUS_MAX_CAPACITY_REACHED = -2;
   public const int STATUS_ALREADY_REGISTERED = -3;
   public const int STATUS_EVENT_IN_PAST = -4;
}
