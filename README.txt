=== Plugin Info ===
Sheikh Khalifa Restaurant
Repo: simplifybiz-sheikh-khalifa-restaurant

Custom plugin for the Sheikh Khalifa Restaurant site. Built on the SMPLFY
boilerplate architecture (Repositories / Usecases / Adapters / Presentation).

Depends on: smplfy-core

=== Features ===
- Reservation form submission handling (Gravity Forms form 6)
- Table availability check against inventory + currently-held reservations
- Past slots are automatically freed (date+time in past = 0 tables taken)
- Google Chat notification to the Reservations space on every submission
- Rejection email to customer when manager rejects via Gravity Flow,
  including available alternative times on the same day if any exist
- Role-based body class injection (.role-manager / .role-subscriber etc.)
- Dynamic Login / Log Out nav item on the primary menu
- Branded login page styling overriding MemberPress defaults

=== Architecture ===
smplfy_sheikh_khalifa.php         Main plugin file / entry point
admin/DependencyFactory.php       Wires up dependencies
admin/utilities/                  Require helpers (generic)
includes/smplfy_bootstrap.php     Loads files + kicks off factory
includes/enqueue_scripts.php      Enqueues frontend CSS
public/css/                       frontend.css, login.css
public/php/types/                 FormIds, TableInventory, TimeSlots
public/php/entities/              ReservationEntity
public/php/repositories/          ReservationRepository (extends SMPLFY_BaseRepository)
public/php/usecases/              TableAvailability, Reservation, RejectionEmail
public/php/adapters/              GravityFormsAdapter, GravityFlowAdapter
public/php/presentation/          BodyClass, NavMenu, LoginPageStyles

=== Flow Overview ===
1. Customer submits form 6
   -> GravityFormsAdapter -> Reservation usecase
   -> TableAvailability.check() queries ReservationRepository
   -> Google Chat notification posted (fire-and-forget)

2. Manager approves in Gravity Flow
   -> Entry's workflow_final_status becomes non-pending, non-rejected
   -> Next availability query counts this entry against the slot

3. Manager rejects in Gravity Flow
   -> GravityFlowAdapter -> RejectionEmail usecase
   -> TableAvailability.find_alternatives_for_date() scans same-day times
   -> wp_mail() sends customer a plain-text email with alternatives if any

4. Reservation date+time passes
   -> ReservationRepository.count_holding_reservations_for_slot() returns 0
      for that slot (past slots are treated as free)

=== TODOs ===
1. Verify the "I agree" checkbox sub-ID (currently '18.1') in FormIds and
   ReservationEntity after a real submission lands in the entry list.

2. Verify the Number of Guests dropdown VALUES match TableInventory's
   expected labels ("2 guests", "4 guests", "6 guests", "8 guests",
   "10 guests"). If the dropdown stores bare numbers, update
   TableInventory::map_party_size_label() accordingly.

3. The rejection email is plain text. If a branded HTML email is wanted,
   switch RejectionEmail::send_email() to set Content-Type: text/html
   via the wp_mail_content_type filter and build an HTML body.

=== Debugging ===
Errors are logged to debug-error.txt in the plugin root. View them at:
  https://<site>/wp-content/plugins/simplifybiz-sheikh-khalifa-restaurant/debug-log-reader.php

All plugin-wide logging uses \SmplfyCore\SMPLFY_Log::error().
