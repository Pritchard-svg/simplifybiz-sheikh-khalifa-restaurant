<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handles reservation form submissions — checks availability and posts
 * a Google Chat notification to the Sheikh Khalifa Reservations space.
 */
class Reservation {

    private string $webhook_reservations = 'https://chat.googleapis.com/v1/spaces/AAQAX6kdyXI/messages?key=AIzaSyDdI0hCZtE6vySjMm-WEfRq3CPzqKqqsHI&token=nGLz0rGXIn1BV3wkbUxRv-CUNmHWKURwPxQ1L8dJ3ho';

    private TableAvailability $tableAvailability;

    public function __construct( TableAvailability $tableAvailability ) {
        $this->tableAvailability = $tableAvailability;
    }

    public function handle_reservation_submission( array $entry ): void {

        try {

            $entity = new ReservationEntity( $entry );

            $availability = $this->tableAvailability->check(
                (string) $entity->date,
                (string) $entity->time,
                (string) $entity->partySize,
                (string) $entity->seating
            );

            $this->send_google_chat_notification( $entity, $availability );

        } catch ( \Throwable $e ) {
            \SmplfyCore\SMPLFY_Log::error( 'Reservation submission error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
    }

    private function send_google_chat_notification( ReservationEntity $entity, array $availability ): void {

        $statusIcon = ! empty( $availability['available'] ) ? '✅' : '❌';

        if ( ! empty( $availability['available'] ) ) {
            $availabilityLine = "{$statusIcon} Tables Available — {$availability['remaining']} of {$availability['max']} remaining.";
        } else {
            $availabilityLine = "{$statusIcon} NO TABLES AVAILABLE — Auto rejected.";
        }

        $text  = "🍽️ *New Reservation Request*\n";
        $text .= "────────────────────\n";
        $text .= "*Name:* {$entity->name}\n";
        $text .= "*Email:* {$entity->email}\n";
        $text .= "*Date:* {$entity->date}\n";
        $text .= "*Time:* {$entity->time}\n";
        $text .= "*Guests:* {$entity->partySize}\n";
        $text .= "*Seating:* {$entity->seating}\n";

        if ( ! empty( $entity->specialRequests ) ) {
            $text .= "*Special Requests:* {$entity->specialRequests}\n";
        }

        if ( ! empty( $entity->addons ) ) {
            $text .= "*Add-ons:* {$entity->addons}\n";
        }

        $text .= "────────────────────\n" . $availabilityLine . "\n";

        if ( ! empty( $availability['available'] ) ) {
            $text .= "\n👉 Action required: " . site_url( '/manager-dashboard' );
        }

        wp_remote_post( $this->webhook_reservations, [
            'body'     => wp_json_encode( [ 'text' => $text ] ),
            'headers'  => [ 'Content-Type' => 'application/json; charset=utf-8' ],
            'timeout'  => 0.01,
            'blocking' => false,
        ] );
    }
}
