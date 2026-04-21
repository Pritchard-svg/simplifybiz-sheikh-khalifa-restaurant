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
1. Verify the following field IDs in public/php/types/FormIds.php against
   the actual Reservation form (form 6):
     - RESERVATION_NAME_FIELD_ID
     - RESERVATION_EMAIL_FIELD_ID
     - RESERVATION_SEATING_FIELD_ID
     - RESERVATION_SPECIAL_REQUESTS_FIELD_ID
     - RESERVATION_ADDONS_FIELD_ID
   Then update ReservationEntity.php to match.

2. If name is a compound Gravity Forms "Name" field, change the entity
   mapping to use the subfield notation (e.g. '1.3' + '1.6') and add
   nameFirst / nameLast properties.

=== Debugging ===
Errors are logged to debug-error.txt in the plugin root. View them at:
  https://<site>/wp-content/plugins/simplifybiz-sheikh-khalifa-restaurant/debug-log-reader.php

All plugin-wide logging uses \SmplfyCore\SMPLFY_Log::error().
