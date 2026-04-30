<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Posts a Google Chat notification to the team space when a reservation
 * is approved at the approval step (step 7) via Gravity Flow.
 *
 * Hooked via GravityFlowAdapter — do not register hooks here.
 */
class ReservationApproval {

    private string $webhook_team = 'https://chat.googleapis.com/v1/spaces/AAQAX6kdyXI/messages?key=AIzaSyDdI0hCZtE6vySjMm-WEfRq3CPzqKqqsHI&token=nGLz0rGXIn1BV3wkbUxRv-CUNmHWKURwPxQ1L8dJ3ho';

    public function handle_approval( int $entry_id ): void {

        try {

            if ( ! class_exists( '\GFAPI' ) ) {
                return;
            }

            $entry = \GFAPI::get_entry( $entry_id );

            if ( is_wp_error( $entry ) || empty( $entry ) ) {
                return;
            }

            $entity = new ReservationEntity( $entry );
            $this->send_team_notification( $entity );

        } catch ( \Throwable $e ) {
            \SmplfyCore\SMPLFY_Log::error( 'ReservationApproval error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
        }
    }

    private function send_team_notification( ReservationEntity $entity ): void {

        $fullName = trim( $entity->nameFirst . ' ' . $entity->nameLast );
        if ( $fullName === '' ) {
            $fullName = 'Guest';
        }

        $text  = "✅ *Reservation Approved*\n";
        $text .= "──────────────────────\n\n";
        $text .= "Hello Team,\n\n";
        $text .= "A reservation has been approved. Please prepare for this guest.\n\n";

        $text .= "*Guest Details*\n";
        $text .= "*Name:* {$fullName}\n";
        $text .= "*Email:* {$entity->email}\n";
        $text .= "*Phone:* {$entity->phone}\n\n";

        $text .= "*Reservation Details*\n";
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

        if ( ! empty( $entity->tableAssigned ) || ! empty( $entity->waiterAssigned ) ) {
            $text .= "\n*Assignment*\n";
            if ( ! empty( $entity->tableAssigned ) ) {
                $text .= "*Table:* {$entity->tableAssigned}\n";
            }
            if ( ! empty( $entity->waiterAssigned ) ) {
                $text .= "*Waiter:* {$entity->waiterAssigned}\n";
            }
        }

        wp_remote_post( $this->webhook_team, [
            'body'     => wp_json_encode( [ 'text' => $text ] ),
            'headers'  => [ 'Content-Type' => 'application/json; charset=utf-8' ],
            'timeout'  => 0.01,
            'blocking' => false,
        ] );
    }
}
