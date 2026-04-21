<?php

namespace SMPLFY\sheikh_khalifa;

/**
 * Provides the list of valid reservation time slots by reading the
 * dropdown values directly from the Reservation form's time field.
 *
 * Keeping this dynamic means there's a single source of truth — the form —
 * and the plugin won't drift out of sync if the restaurant changes hours.
 */
class TimeSlots {

    /**
     * Returns the list of time slot values as defined in form 6's time dropdown.
     * Returns an empty array if Gravity Forms isn't loaded or the form/field is missing.
     *
     * @return string[]
     */
    public static function get_all(): array {

        if ( ! class_exists( '\GFAPI' ) ) {
            return [];
        }

        $form = \GFAPI::get_form( FormIds::RESERVATION_FORM_ID );

        if ( ! is_array( $form ) || empty( $form['fields'] ) ) {
            return [];
        }

        foreach ( $form['fields'] as $field ) {

            if ( (string) $field->id !== (string) FormIds::RESERVATION_TIME_FIELD_ID ) {
                continue;
            }

            $values = [];

            foreach ( (array) ( $field->choices ?? [] ) as $choice ) {
                // Prefer 'value' (what gets stored in entries) over 'text' (display label).
                $value = $choice['value'] ?? $choice['text'] ?? '';
                if ( $value !== '' ) {
                    $values[] = (string) $value;
                }
            }

            return $values;
        }

        return [];
    }
}
