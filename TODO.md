# 1. DATABASE USER FOR MARIADB! => DONE
- Create a bash script for that.

---

# 2. Create tables / models / factories.
- Players
- Teams

1. Create the migrations:
    - php artisan make:migration create_teams_table --create=teams => DONE
    - php artisan make:migration create_players_table --create=players => DONE
2. Edit migration files with the function "up()" => DONE
3. Do the migration: => DONE
    - php artisan migrate => DONE
4. Create the models: => DONE
    - php artisan make:model Team => DONE
    - php artisan make:model Player => DONE
5. Create the factories: => DONE
    - php artisan make:factory TeamFactory => DONE
    - php artisan make:factory PlayerFactory => DONE
6. Fill the definition function with faker. => DONE
7. Create seeders: => DONE
    - php artisan make:seeder TeamsTableSeeder => DONE
    - php artisan make:seeder PlayersTableSeeder => DONE

---

# 3. Create controllers for each of the main menu option (Each main menu option corresponds to a model!)
- TODO: Describe it.

# 4. FRONT END!
## HEADER
- With a banner of football or something. => DONE

## NAVIGATION BAR

- Home
- Manage Teams
    - "Add team button"
        - Form with: => DONE
            · id (disabled, autoincrement) => DONE
            · name (string, unique) => DONE
            · coach (string) => DONE
            · category (string) => DONE
            · budget (double) => DONE
            · Two buttons:
                - "Add new team" => DONE
                - "Cancel" -> Brings back to the Manage teams menu option. => DONE
    - Data table with the all the teams, each line has the following: => DONE
        · Name => DONE
        · Coach => DONE
        · Team category => DONE
        · Actions (Buttons) => DONE
            - Edit: => DONE
                - Form with: => DONE
                    · id (disabled, autoincrement) -> In the edit form must contain the => DONE
                                                      ID of the team which we are editing.
                    · name (string, unique) => DONE
                    · coach (string) => DONE
                    · category (string) => DONE
                    · budget (double) => DONE
                    · Two buttons: => DONE
                        - "Update" / "Modify" => DONE
                        - "Cancel" -> Brings back to the Manage teams menu option. => DONE
                - "Subscribe player" button which will redirect to the "Enroll player" view.=> DONE, !!!!!!! BUT MISSING THE FUNCTIONALITY!!!!!!!!
                - Data table under the form with all the subscribed players of the => DONE
                  team and the number of displayed players:
                    · Player name => DONE
                    · Button to "unsubscribe" => DONE
            - Delete: => DONE
                (Must redirect to a confirmation page. If the team has players,
                the action won't let the deletion, and in either case will inform
                the user about the outcome and the cause of it ...)

    - FROM "Subscribe player" -> Enroll player:
        Data table with the followings:
            · First name
            · Last name
            · Team subscribed to
            · Button to subscribe:
                (If the user is already subscribed to a team, then ask for confirmation to
                unsubscribe from that team and subscribe to the requested new one.
                MUST MAKE SURE THAT ALL THE OPERATIONS ARE DONE IN THE CHAIN!!!)

---

- Manage players:
    - "Add player" button, which will redirect to the "Add player" view form.
            · id (disabled, autoincrement)
            · first name (string)
            · last name (string)
            · date of birth (integer)
            · salary (double)
            · "Add player" button
            · "Cancel" button, which will redirect to the "Manage players"
    - Data table with all the players and the number of players displayed:
        · first name
        · last name
        · date of birth
        · "Edit" button -> redirect to the "Edit player" view form.
        · "Delete" button -> Confirmation page:
                                (If the player belongs to a team, the action
                                should not be allowed, as he must be removed
                                from the team before he can be deleted.
                                Warn the user about the outcome and its cause.)
    - FROM "Edit player" button -> "Add player form":
        · id (disabled, autoincrement)
        · first name (string)
        · last name (string)
        · date of birth (integer)
        · salary (double)
        · "Update / Modify player" button
        · "Cancel" button, which will redirect to the "Manage players"

## FOOTER
- copyright
- some made up sponsors (use icons or something...)



