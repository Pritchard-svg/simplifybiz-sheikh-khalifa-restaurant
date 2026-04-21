<?php

namespace SMPLFY\sheikh_khalifa;

class DependencyFactory {

    private static bool $initialized = false;

    static function create_plugin_dependencies(): void {

        if ( self::$initialized ) {
            return;
        }
        self::$initialized = true;

        $gravityFormsApi = new \SmplfyCore\SMPLFY_GravityFormsApiWrapper();

        // Repositories
        $reservationRepository = new ReservationRepository( $gravityFormsApi );

        // Usecases
        $tableAvailability = new TableAvailability( $reservationRepository );
        $reservation       = new Reservation( $tableAvailability );

        // Adapters
        new GravityFormsAdapter( $reservation );

        // Presentation
        new BodyClass();
        new NavMenu();
        new LoginPageStyles();
    }
}
