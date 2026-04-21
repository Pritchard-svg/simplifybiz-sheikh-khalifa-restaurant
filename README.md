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
