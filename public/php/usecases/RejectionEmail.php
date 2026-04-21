<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * When a reservation is rejected via Gravity Flow, emails the customer.
 * If there are available alternative time slots on the same date, lists them.
 *
 * Hooked via GravityFlowAdapter — do not register hooks here.
 */
class RejectionEmail {

    private TableAvailability $tableAvailability;

    public function __construct( TableAvailability $tableAvailability ) {
        $this->tableAvailability = $tableAvailability;
    }

    public function handle_rejection( int $entry_id ): void {

        try {

            if ( ! class_exists( '\GFAPI' ) ) {
                return;
            }

            $entry = \GFAPI::get_entry( $entry_id );

            if ( is_wp_error( $entry ) || empty( $entry ) ) {
                return;
            }

            $entity = new ReservationEntity( $entry );

            if ( empty( $entity->email ) ) {
                \SmplfyCore\SMPLFY_Log::error( 'RejectionEmail: entry ' . $entry_id . ' has no email. Skipping.' );
                return;
            }

            $alternatives = $this->tableAvailability->find_alternatives_for_date(
                (string) $entity->date,
                (string) $entity->partySize,
                (string) $entity->time
            );

            $this->send_email( $entity, $alternatives );

        } catch ( \Throwable $e ) {
            \SmplfyCore\SMPLFY_Log::error( 'RejectionEmail error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
    }

    /**
     * @param array<int, array{time: string, seating: string[]}> $alternatives
     */
    private function send_email( ReservationEntity $entity, array $alternatives ): void {

        $fullName = trim( $entity->nameFirst . ' ' . $entity->nameLast );
        if ( $fullName === '' ) {
            $fullName = 'there';
        }

        $siteName = get_bloginfo( 'name' );

        $subject = 'Your reservation request at ' . $siteName;

        $body  = "Hi {$fullName},\n\n";
        $body .= "Thank you for your reservation request for {$entity->date} at {$entity->time} for {$entity->partySize}.\n\n";
        $body .= "Unfortunately, we were unable to accommodate your reservation at that time.\n\n";

        if ( empty( $alternatives ) ) {

            $body .= "We also do not have any other availability on {$entity->date} for your party size. ";
            $body .= "We'd love to host you on another day — please feel free to submit a new reservation request.\n\n";

        } else {

            $body .= "However, we do have availability on the same day at these times:\n\n";

            foreach ( $alternatives as $alt ) {
                $seating = implode( ' or ', $alt['seating'] );
                $body   .= "  - {$alt['time']} ({$seating})\n";
            }

            $body .= "\nTo book one of these times, please submit a new reservation request.\n\n";
        }

        $body .= "Thank you,\n";
        $body .= $siteName;

        $sent = wp_mail( $entity->email, $subject, $body );

        if ( ! $sent ) {
            \SmplfyCore\SMPLFY_Log::error( 'RejectionEmail: wp_mail returned false for ' . $entity->email );
        }
    }
}
