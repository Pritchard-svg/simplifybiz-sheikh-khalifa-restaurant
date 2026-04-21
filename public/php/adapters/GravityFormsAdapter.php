<?php

namespace SMPLFY\sheikh_khalifa;

class GravityFormsAdapter {

    private Reservation $reservation;

    public function __construct( Reservation $reservation ) {
        $this->reservation = $reservation;

        $this->register_hooks();
    }

    private function register_hooks(): void {

        add_action(
            'gform_after_submission_' . FormIds::RESERVATION_FORM_ID,
            function( $entry, $form ) {
                $this->reservation->handle_reservation_submission( $entry );
            },
            10,
            2
        );
    }
}
