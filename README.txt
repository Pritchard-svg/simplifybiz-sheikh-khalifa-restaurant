=== Plugin Info ===
Sheikh Khalifa Restaurant
Repo: simplifybiz-sheikh-khalifa-restaurant

Custom plugin for the Sheikh Khalifa Restaurant site. Built on the SMPLFY
boilerplate architecture (Repositories / Usecases / Adapters / Presentation).

Depends on: smplfy-core

=== Features ===
- Reservation form submission handling (Gravity Forms form 6)
- Table availability check against inventory + currently-held reservations
- Google Chat notification to the Reservations space on every submission
- Role-based body class injection (enables .role-manager / .role-subscriber etc.)
- Dynamic Login / Log Out nav item on the primary menu
- Branded login page styling overriding MemberPress defaults

=== Architecture ===
smplfy_sheikh_khalifa.php         Main plugin file / entry point
admin/DependencyFactory.php       Wires up dependencies
admin/utilities/                  Require helpers (generic)
includes/smplfy_bootstrap.php     Loads files + kicks off factory
includes/enqueue_scripts.php      Enqueues frontend CSS
public/css/                       frontend.css, login.css
public/php/types/                 FormIds, TableInventory (reference data)
public/php/entities/              ReservationEntity
public/php/repositories/          ReservationRepository (extends SMPLFY_BaseRepository)
public/php/usecases/              TableAvailability, Reservation (business logic)
public/php/adapters/              GravityFormsAdapter (hooks GF -> usecases)
public/php/presentation/          BodyClass, NavMenu, LoginPageStyles (self-registering UI)

=== TODOs ===
1. Verify the "I agree" checkbox sub-ID in FormIds::RESERVATION_AGREEMENT_FIELD_ID
   and ReservationEntity. Currently assumed to be '18.1' (Gravity Forms convention
   for single-choice checkbox values).

2. Verify the Number of Guests dropdown option VALUES match the labels expected
   by TableInventory::map_party_size_label ("2 guests", "4 guests", "6 guests",
   "8 guests", "10 guests"). If the form uses different values (e.g. just "2",
   "4"), update the map_party_size_label method accordingly.

=== Debugging ===
Errors are logged to debug-error.txt in the plugin root. View them at:
  https://<site>/wp-content/plugins/simplifybiz-sheikh-khalifa-restaurant/debug-log-reader.php

All plugin-wide logging uses \SmplfyCore\SMPLFY_Log::error().
