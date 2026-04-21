<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Determines whether a reservation slot has tables available
 * based on the restaurant's inventory and currently-held reservations.
 */
class TableAvailability {

    private ReservationRepository $reservationRepository;

    public function __construct( ReservationRepository $reservationRepository ) {
        $this->reservationRepository = $reservationRepository;
    }

    public function check( string $date, string $time, string $partySizeLabel, string $seatingPreference ): array {

        $inventory = TableInventory::get_all();
        $size      = TableInventory::map_party_size_label( $partySizeLabel );

        if ( ! $size || ! isset( $inventory[ $size ] ) ) {
            return [ 'available' => false, 'message' => 'Invalid party size.' ];
        }

        $taken   = $this->reservationRepository->count_holding_reservations_for_slot( $date, $time, $partySizeLabel );
        $seating = strtolower( $seatingPreference );

        if ( $seating === 'indoor' ) {
            $max = $inventory[ $size ]['indoor'];
        } elseif ( $seating === 'outdoor' ) {
            $max = $inventory[ $size ]['outdoor'];
        } else {
            $max = $inventory[ $size ]['total'];
        }

        $remaining = $max - $taken;

        if ( $remaining <= 0 ) {
            return [
                'available' => false,
                'taken'     => $taken,
                'max'       => $max,
                'remaining' => 0,
                'message'   => 'No tables available for this slot.',
            ];
        }

        return [
            'available' => true,
            'taken'     => $taken,
            'max'       => $max,
            'remaining' => $remaining,
            'message'   => $remaining . ' table(s) remaining for this slot.',
        ];
    }
}
