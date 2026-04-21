<?php

namespace SMPLFY\sheikh_khalifa;

use SmplfyCore\SMPLFY_BaseRepository;
use SmplfyCore\SMPLFY_GravityFormsApiWrapper;
use WP_Error;

/**
 * @method static ReservationEntity|null get_one( $fieldId, $value )
 * @method static ReservationEntity|null get_one_for_current_user()
 * @method static ReservationEntity|null get_one_for_user( $userId )
 * @method static ReservationEntity[] get_all( $fieldId = null, $value = null, string $direction = 'ASC' )
 * @method static int|WP_Error add( ReservationEntity $entity )
 */
class ReservationRepository extends SMPLFY_BaseRepository {

    public function __construct( SMPLFY_GravityFormsApiWrapper $gravityFormsApi ) {
        $this->entityType = ReservationEntity::class;
        $this->formId     = FormIds::RESERVATION_FORM_ID;

        parent::__construct( $gravityFormsApi );
    }

    /**
     * Counts reservations currently holding a table for the given slot.
     *
     * Only counts entries whose Gravity Flow workflow has finished with a
     * non-rejected outcome — pending/in-progress/rejected do NOT count
     * against availability.
     *
     * Also returns 0 if the queried slot's date+time is already in the past,
     * so tables automatically "return to availability" once the reservation
     * time has elapsed.
     */
    public function count_holding_reservations_for_slot( string $date, string $time, string $partySize ): int {

        if ( ! class_exists( '\GFAPI' ) ) {
            return 0;
        }

        // If the queried slot is in the past, treat all tables as available.
        if ( $this->is_slot_in_past( $date, $time ) ) {
            return 0;
        }

        $entries = \GFAPI::get_entries( $this->formId, [
            'status'        => 'active',
            'field_filters' => [
                'mode' => 'all',
                [ 'key' => FormIds::RESERVATION_DATE_FIELD_ID,       'value' => $date ],
                [ 'key' => FormIds::RESERVATION_TIME_FIELD_ID,       'value' => $time ],
                [ 'key' => FormIds::RESERVATION_PARTY_SIZE_FIELD_ID, 'value' => $partySize ],
                [ 'key' => 'workflow_final_status', 'value' => 'rejected', 'operator' => 'isnot' ],
                [ 'key' => 'workflow_final_status', 'value' => 'pending',  'operator' => 'isnot' ],
            ],
        ] );

        return is_array( $entries ) ? count( $entries ) : 0;
    }

    /**
     * Returns true if the given date+time is in the past relative to the
     * site's configured timezone. Returns false if the strings can't be parsed
     * (safer to assume future — the caller will still hit the DB count).
     */
    private function is_slot_in_past( string $date, string $time ): bool {

        if ( $date === '' || $time === '' ) {
            return false;
        }

        try {
            $timezone     = wp_timezone();
            $slotDateTime = new \DateTimeImmutable( $date . ' ' . $time, $timezone );
            $now          = new \DateTimeImmutable( 'now', $timezone );
            return $slotDateTime < $now;
        } catch ( \Throwable $e ) {
            \SmplfyCore\SMPLFY_Log::error( 'ReservationRepository could not parse slot date/time: ' . $e->getMessage() );
            return false;
        }
    }
}
