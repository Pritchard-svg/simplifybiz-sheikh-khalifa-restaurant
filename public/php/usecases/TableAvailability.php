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

    /**
     * Checks availability for a single slot.
     *
     * @return array{available: bool, taken?: int, max?: int, remaining?: int, message: string}
     */
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

    /**
     * Finds available alternative time slots on the same date for the given
     * party size. Checks both indoor and outdoor seating at each time, skips
     * slots that are already in the past, and excludes the originally-requested
     * time (which is presumed unavailable since that's why we're looking for alternatives).
     *
     * @return array<int, array{time: string, seating: string[]}>
     */
    public function find_alternatives_for_date( string $date, string $partySizeLabel, string $excludeTime = '' ): array {

        $times        = TimeSlots::get_all();
        $alternatives = [];

        if ( empty( $times ) ) {
            return $alternatives;
        }

        $timezone = wp_timezone();
        $now      = new \DateTimeImmutable( 'now', $timezone );

        foreach ( $times as $time ) {

            if ( $time === $excludeTime ) {
                continue;
            }

            // Skip slots already in the past.
            try {
                $slotDateTime = new \DateTimeImmutable( $date . ' ' . $time, $timezone );
                if ( $slotDateTime <= $now ) {
                    continue;
                }
            } catch ( \Throwable $e ) {
                continue;
            }

            $indoor  = $this->check( $date, $time, $partySizeLabel, 'indoor' );
            $outdoor = $this->check( $date, $time, $partySizeLabel, 'outdoor' );

            $seatingOptions = [];
            if ( ! empty( $indoor['available'] ) )  { $seatingOptions[] = 'indoor';  }
            if ( ! empty( $outdoor['available'] ) ) { $seatingOptions[] = 'outdoor'; }

            if ( ! empty( $seatingOptions ) ) {
                $alternatives[] = [
                    'time'    => $time,
                    'seating' => $seatingOptions,
                ];
            }
        }

        return $alternatives;
    }
}
