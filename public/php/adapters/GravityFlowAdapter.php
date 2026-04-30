<?php

namespace SMPLFY\sheikh_khalifa;

class GravityFlowAdapter {

    private ReservationApproval $reservationApproval;

    public function __construct( ReservationApproval $reservationApproval ) {
        $this->reservationApproval = $reservationApproval;

        $this->register_hooks();
    }

    private function register_hooks(): void {

        add_action(
            'gravityflow_step_complete',
            [ $this, 'handle_step_complete' ],
            10,
            4
        );
    }

    /**
     * Fires the team notification when the Reservation form's approval step
     * (step 7) is completed with an 'approved' status. All other step
     * completions are ignored.
     *
     * @param int    $step_id
     * @param int    $entry_id
     * @param int    $form_id
     * @param string $status
     */
    public function handle_step_complete( $step_id, $entry_id, $form_id, $status ): void {

        if ( (int) $form_id !== FormIds::RESERVATION_FORM_ID ) {
            return;
        }

        if ( (string) $status === 'approved' && (int) $step_id === FormIds::RESERVATION_APPROVAL_STEP_ID ) {
            $this->reservationApproval->handle_approval( (int) $entry_id );
        }
    }
}
