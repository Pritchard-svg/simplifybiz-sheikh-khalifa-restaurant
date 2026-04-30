<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Posts a Google Chat notification to the reservations space
 * whenever the Reservation form is submitted.
 */
class Reservation {

    private string $webhook_reservations = 'https://chat.googleapis.com/v1/spaces/AAQAX6kdyXI/messages?key=AIzaSyDdI0hCZtE6vySjMm-WEfRq3CPzqKqqsHI&token=nGLz0rGXIn1BV3wkbUxRv-CUNmHWKURwPxQ1L8dJ3ho';

    public function handle_reservation_submission( array $entry ): void {

        try {

            $entity = new ReservationEntity( $entry );
            $this->send_google_chat_notification( $entity );

        } catch ( \Throwable $e ) {
            \SmplfyCore\SMPLFY_Log::error( 'Reservation submission error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
    }

    private function send_google_chat_notification( ReservationEntity $entity ): void {

        $fullName = trim( $entity->nameFirst . ' ' . $entity->nameLast );

        $text  = "🍽️ *New Reservation Request*\n";
        $text .= "────────────────────\n";
        $text .= "*Name:* {$fullName}\n";
        $text .= "*Email:* {$entity->email}\n";
        $text .= "*Phone:* {$entity->phone}\n";
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

        wp_remote_post( $this->webhook_reservations, [
            'body'     => wp_json_encode( [ 'text' => $text ] ),
            'headers'  => [ 'Content-Type' => 'application/json; charset=utf-8' ],
            'timeout'  => 0.01,
            'blocking' => false,
        ] );
    }
}
