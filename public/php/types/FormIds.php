<?php

namespace SMPLFY\sheikh_khalifa;

class FormIds {

    public const RESERVATION_FORM_ID = 6;

    // ---- Personal Details section ----
    public const RESERVATION_NAME_FIRST_FIELD_ID       = '2.3';
    public const RESERVATION_NAME_LAST_FIELD_ID        = '2.6';
    public const RESERVATION_EMAIL_FIELD_ID            = '3';
    public const RESERVATION_PHONE_FIELD_ID            = '4';

    // ---- Reservation Details section ----
    public const RESERVATION_DATE_FIELD_ID             = '6';
    public const RESERVATION_TIME_FIELD_ID             = '7';
    public const RESERVATION_PARTY_SIZE_FIELD_ID       = '9';
    public const RESERVATION_SEATING_FIELD_ID          = '8';

    // ---- Extras section ----
    public const RESERVATION_SPECIAL_REQUESTS_FIELD_ID = '12';
    public const RESERVATION_ADDONS_FIELD_ID           = '17';

    // Gravity Forms stores single-choice checkbox values under sub-ID .1.
    // TODO: verify this matches the "I agree" field's actual sub-ID.
    public const RESERVATION_AGREEMENT_FIELD_ID        = '18.1';

    // ---- Manager Only section (populated during Gravity Flow workflow) ----
    public const RESERVATION_TABLE_ASSIGNED_FIELD_ID   = '20';
    public const RESERVATION_WAITER_ASSIGNED_FIELD_ID  = '21';
    public const RESERVATION_REJECTION_REASON_FIELD_ID = '22';
}
