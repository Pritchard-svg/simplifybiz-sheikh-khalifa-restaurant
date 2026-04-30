<?php

namespace SMPLFY\sheikh_khalifa;

class DependencyFactory {

    private static bool $initialized = false;

    static function create_plugin_dependencies(): void {

        if ( self::$initialized ) {
            return;
        }
        self::$initialized = true;

        // Usecases
        $reservation         = new Reservation();
        $reservationApproval = new ReservationApproval();

        // Adapters
        new GravityFormsAdapter( $reservation );
        new GravityFlowAdapter( $reservationApproval );

        // Presentation
        new BodyClass();
        new NavMenu();
        new LoginPageStyles();
    }
}
