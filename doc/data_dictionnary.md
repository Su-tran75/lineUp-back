# Data

## Table user

| Champ | Type | Spécificités | Description |
| - | - | - | - |
| id | INT(11) | NOT NULL, AUTO INCREMENT |  |
| email | VARCHAR(255) | NOT NULL, UNIQUE |  |
| phone_number | VARCHAR(20) | NOT NULL | VARCHAR, if INT used that will delete the first 0 (06xxxxxxxx) |
| firstname | VARCHAR(255) | NOT NULL |  |
| lastname | VARCHAR(255) | NOT NULL |  |
| password | VARCHAR(255) | NOT NULL |  |
| roles | longtext | NOT NULL | (DC2Type:json), ["ROLE_USER"], ["ROLE_RESTAURANT"], ["ROLE_ADMIN"] |
| avatar | longtext | NULLABLE | longtext for image b64 |
| favorite | VARCHAR(255) | NULLABLE | Favorite's restaurant |
| restaurant_id | INT(11) | NULLABLE | Useful when the user is a restaurant's owner. OneToOne between User and Restaurant. 1 user can have 1 restaurant. 1 restaurant can have 1 user. |
| tickets | INT(11) | NULLABLE | ManyToOne between User and Tickets, 1 user can have many tickets. 1 ticket can have 1 user only |
| current_ticket | INT(11) | NULLABLE | Used for the last active ticket |
| created_at | DATETIME | NOT NULL |  |
| updated_at | DATETIME | NULLABLE |  |

## Table restaurant

| Champ | Type | Spécificités | Description |
| - | - | - | - |
| id | INT(11) | NOT NULL, AUTO INCREMENT |  |
| name | VARCHAR(255) | NOT NULL |  |
| adress | VARCHAR(255) | NOT NULL | Number and street |
| zip | VARCHAR(20) | NOT NULL |  |
| city | VARCHAR(20) | NOT NULL |  |
| phone_number | VARCHAR(20) | NOT NULL | VARCHAR, if INT used that will delete the first 0 (06xxxxxxxx) |
| siret | BIGINT(20) | NOT NULL |  |
| trade_name | VARCHAR(255) | NOT NULL |  |
| picture | longtext | NULLABLE | longtext for image b64 |
| time_interval | INT(11) | NOT NULL | Time between 2 tickets |
| status | INT(11) | NOT NULL | 0 = don't take orders, 1 = take orders |
| slug | VARCHAR(255) | NULLABLE |  |
| created_at | DATETIME | NOT NULL |  |
| updated_at | DATETIME | NULLABLE |  |
| cuisine_type_id | INT(11) | NULLABLE | ManyToOne betwen Restaurant and CuisineType. 1 restaurant can have 1 cuisine_type. 1 cuisine_type can have many restaurant |

## Table ticket

| Champ | Type | Spécificités | Description |
| - | - | - | - |
| id | INT(11) | NOT NULL, AUTO INCREMENT |  |
| guest | INT(11) | NOT NULL |  |
| name | VARCHAR(255) | NULLABLE | Usefull when anonymous Tickets |
| comment | VARCHAR(255) | NULLABLE |  |
| estimated_time | INT(11) | NOT NULL | = time_interval * "tickets before me" |
| status | INT(11) | NOT NULL | 0 = archived, 1 = active |
| created_at | DATETIME | NOT NULL |  |
| updated_at | DATETIME | NULLABLE |  |
| user_id | INT(11) | NULLABLE | null when anonymous user. ManyToOne between Ticket and User. 1 ticket can have 1 user. 1 user can have many tickets |
| restaurant_id | INT(11) | NULLABLE | ManyToOne between Ticket and Restaurant. 1 ticket can have 1 restaurant. 1 restaurant can have many tickets |

## Table cuisinetype

| Champ | Type | Spécificités | Description |
| - | - | - | - |
| id | INT(11) | NOT NULL, AUTO INCREMENT |  |
| name | VARCHAR(255) | NOT NULL |  |
| created_at | DATETIME | NOT NULL |  |
| updated_at | DATETIME | NULLABLE |  |
