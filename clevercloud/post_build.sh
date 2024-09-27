echo "Start post_build scripts"

./bin/console messenger:stop-workers

echo ${GOOGLE_CREDENTIALS} > ./resources/auth/credentials.json
chmod -R ugo+r ./resources/auth

./bin/console doctrine:migrations:migrate --no-interaction

php bin/console app:update:labels
php bin/console app:update:games_collections_and_dex
php bin/console app:update:pokemons
php bin/console app:update:regional_dex_numbers
echo "Pause 1 60s" && sleep 60s && echo "Resume 1 "
php bin/console app:update:games_availabilities
php bin/console app:update:games_shinies_availabilities
php bin/console app:update:collections_availabilities
echo "Pause 2 60s" && sleep 60s && echo "Resume 2"
php bin/console app:calculate:game_bundles_availabilities
php bin/console app:calculate:game_bundles_shinies_availabilities
php bin/console app:calculate:dex_availabilities
php bin/console app:calculate:pokemon_availabilities

echo "End post_build scripts"
