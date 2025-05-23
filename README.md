# Pokémon Living/Alternate/Gender Extended Dex!

## To Begin

### Prerequistes

#### NVD API KEY

In order to update OWAP records, you need an NVD API Key. You can request one on https://nvd.nist.gov/developers/request-an-api-key.
Then define it to your env with

```
export NVD_API_KEY=374dc342-3ca3-47a7-8133-794cb256e581
```

### TL;DR

```
make stop build start quality tests integration
```
or
```
make quality tests integration
```

### Install

```
make start
```

### Restart

```
make stop start
```

## Tips

### Fake authentication

Id dev environnement only, you can use a fake authenticator to avoid using Oauth2.  
Go to 
* http://localhost/fr/connect/f/c?t=admin to set an *admin* session
* http://localhost/fr/connect/f/c?t=collector to set a *collector* session
* http://localhost/fr/connect/f/c?t=trainer to set a *trainer* session

### Open bash into php  container

```
make sh
```

`exit` in it to quit.

### Composer

To install a package

```
make composer c="require gedmo/doctrine-extensions"
```

### Debug easily

To save html into a file that you can open with your browser

```php
file_put_contents('tests/last.html', $client->getCrawler()->html());
```

### Adminer

[http://localhost:8082/?pgsql=database&username=app&db=app&ns=public]()

### PHPStan baseline

To update the `phpstan-baseline.neon` file

```
make sh
tools/phpstan/vendor/bin/phpstan --generate-baseline --memory-limit=-1
```

### Docker Image build

```shell
docker login --username RenaudDouze --password ghp_token ghcr.io
```

```shell
docker build --target php_prod -f ./docker/php/Dockerfile -t ghcr.io/douzeensemble/pokenini:latest .
docker push ghcr.io/douzeensemble/pokenini:latest
```
or

```shell
make img-build
```

### Restore Postgresl dump (pg_dump)

```shell
cat postgresql_database.dump | docker compose exec -iT database pg_restore -U app -d app --no-privileges --no-owner -x
```

```shell
cat postgresql_database.dump | docker exec -i $(docker ps --filter name=pokenini-release_database --format "{{.ID}}") pg_restore -U app -d app --no-privileges --no-owner -x
```

## Labels, games and dex

```
php bin/console app:update:labels
php bin/console app:update:games_collections_and_dex
```

## Pokémons

### Import pokemon list

```
docker-compose exec php sh -c '
    php bin/console app:update:pokemons
'
```

### Import regional dex number list

```
docker-compose exec php sh -c '
    php bin/console app:update:regional_dex_numbers
'
```

### Import bulbapedia's games' availabilty list

```
docker-compose exec php sh -c '
    php bin/console app:update:games_availabilities
'
```

### Import bulbapedia's games' shinies' availabilty list

```
docker-compose exec php sh -c '
    php bin/console app:update:games_shinies_availabilities
'
```

### Calculate games' bundles' availabilty

Game bundle availability are calculated from games' availabilities

```
docker-compose exec php sh -c '
    php bin/console app:calculate:game_bundles_availabilities
'
```

### Calculate games' bundles' shinies' availabilty

Game bundle shiny availability are calculated from games' shiny' availabilities

```
docker-compose exec php sh -c '
    php bin/console app:calculate:game_bundles_shinies_availabilities
'
```

### Calculate dex' availabilty

Dex availability are calculated from dex rules

```
docker-compose exec php sh -c '
    php bin/console app:calculate:dex_availabilities
'
```

### Calculate pokemons' availabilties

Pokemons availability are calculated from game bundles and game bundles shiny

```
docker-compose exec php sh -c '
    php bin/console app:calculate:pokemon_availabilities
'
```

### Tips

### Open bash into php  container

```
make sh
```

`exit` in it to quit.

### Composer

To install a package

```
make composer c="require gedmo/doctrine-extensions"
```

### Reset database and migrations

Reset database and redo all migrations
```
make init_db
```

Generate full migration as database is empty. You will have to copy to the first one to avoid issues
```
docker-compose exec php sh -c '
    php bin/console doct:migr:diff --from-empty-schema --no-interaction
'
```

To execute a migration over and over
```
docker-compose exec php sh -c '
    php bin/console doct:migr:exec 'DoctrineMigrations\\Version20221113212114' --down --no-interaction && php bin/console doct:migr:exec 'DoctrineMigrations\\Version20221113212114' --up --no-interaction
'
```

### Send and Consume a message

```
make sf c="messenger:stop-workers" && \
curl -X POST --insecure  "http://localhost/api/istration/update/labels" \
  -H 'Authorization: Basic d2ViOmRvdXpl' \
  -H 'cache-control: no-cache' && \
make sf c="messenger:consume async -vv --limit=1"
```

### Process

#### Change a pokemon slug

1. Look, in "Dex" sheet, for the slug. Replace it by the new one
2. Look, in "Games Availability" sheet, for the slug. Replace it by the new one
3. Look, in "Games Shinies Availability" sheet, for the slug. Replace it by the new one
4. Look, in "Collections Availability" sheet, for the slug. Replace it by the new one
5. Look, in "Regional Dex Number" sheet, for the slug. Replace it by the new one
6. Look, in "Pokémons" sheet, for the slug.
    a. For icon name,
        1. you will need to change it into the sheet into "Icon" column
        2. if not automatically updated, change it into "Sprites url"
        3. if not automatically updated, change it into "Shiny Sprites url"
        4. if not automatically updated, change it into "PokemonDB icon name"
        5. and into the icon repository, use the copy method to avoid missing image
```
mv images/big/regular/pumpkaboo.png images/big/regular/pumpkaboo-average.png
mv images/big/regular/pumpkaboo.webp images/big/regular/pumpkaboo-average.webp
mv images/big/shiny/pumpkaboo.png images/big/shiny/pumpkaboo-average.png
mv images/big/shiny/pumpkaboo.webp images/big/shiny/pumpkaboo-average.webp
mv images/small/regular/pumpkaboo.png images/small/regular/pumpkaboo-average.png
mv images/small/regular/pumpkaboo.webp images/small/regular/pumpkaboo-average.webp
mv images/small/shiny/pumpkaboo.png images/small/shiny/pumpkaboo-average.png
mv images/small/shiny/pumpkaboo.webp images/small/shiny/pumpkaboo-average.webp
```

1. Check if the new slug is not used
```sql
SELECT		*
FROM			pokemon
WHERE			slug = 'new-slug'
```
1. Execute this query to change the slug
```sql
UPDATE 	pokemon
SET		slug = 'new-slug'
WHERE 	slug = 'old-slug'
```
1. Check that the new slug is uptodate
```sql
SELECT		*
FROM			pokemon
WHERE			slug = 'new-slug'
```
1. Update pokemons data in https://www.pokenini.fr/fr/istration page
2. Check into an album if slug is ok by checking html source code
3. Check into an album if icon is ok by checking html source code
4. Delete original icon name into the icon repository

### Debug 

### Check if json are valid or not

Dans le container (`make sh`)

``` bash
find tests/resources/moco -type f -name "*.json" -exec tools/jsonlint/vendor/bin/jsonlint {} \;
```

#### Integration

Get json

```shell
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/redgreenblueyellow" --insecure --output tests/tmp/redgreenblueyellow.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/goldsilvercrystal" --insecure --output tests/tmp/goldsilvercrystal.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/rubysapphireemerald" --insecure --output tests/tmp/rubysapphireemerald.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/fireredleafgreen" --insecure --output tests/tmp/fireredleafgreen.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/diamondpearlplatinium" --insecure --output tests/tmp/diamondpearlplatinium.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/heartgoldsoulsilver" --insecure --output tests/tmp/heartgoldsoulsilver.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/blackwhite" --insecure --output tests/tmp/blackwhite.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/black2white2" --insecure --output tests/tmp/black2white2.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/xy" --insecure --output tests/tmp/xy.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/omegarubyalphasapphire" --insecure --output tests/tmp/omegarubyalphasapphire.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/sunmoon" --insecure --output tests/tmp/sunmoon.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/ultrasunultramoon" --insecure --output tests/tmp/ultrasunultramoon.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/letsgopikachuletsgoeevee" --insecure --output tests/tmp/letsgopikachuletsgoeevee.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/swordshield" --insecure --output tests/tmp/swordshield.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/brilliantdiamondshiningpearl" --insecure --output tests/tmp/brilliantdiamondshiningpearl.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/pokemonlegendsarceus" --insecure --output tests/tmp/pokemonlegendsarceus.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home" --insecure --output tests/tmp/home.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/homeshiny" --insecure --output tests/tmp/homeshiny.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/homepokemongo" --insecure --output tests/tmp/homepokemongo.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/alpha" --insecure --output tests/tmp/alpha.json
curl -u web:douze "https://localhost:4431/api/album/7b52009b64fd0a2a49e6d8a939753077792b0554/mega" --insecure --output tests/tmp/mega.json
```

## Update moco mock from Pokénin-Api

```
curl -u web:douze "https://localhost:4431/catch_states" --insecure --output tests/resources/moco/Web/catch_states.json --header 'Accept: application/json'
curl -u web:douze "https://localhost:4431/types" --insecure --output tests/resources/moco/Web/types.json --header 'Accept: application/json'
curl -u web:douze "https://localhost:4431/forms/category" --insecure --output tests/resources/moco/Web/category_forms.json --header 'Accept: application/json'
curl -u web:douze "https://localhost:4431/forms/regional" --insecure --output tests/resources/moco/Web/regional_forms.json --header 'Accept: application/json'
curl -u web:douze "https://localhost:4431/forms/special" --insecure --output tests/resources/moco/Web/special_forms.json --header 'Accept: application/json'
curl -u web:douze "https://localhost:4431/forms/variant" --insecure --output tests/resources/moco/Web/variant_forms.json --header 'Accept: application/json'
curl -u web:douze "https://localhost:4431/game_bundles" --insecure --output tests/resources/moco/Web/game_bundles.json --header 'Accept: application/json'


curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/redgreenblueyellow" --insecure --output tests/resources/moco/Web/album/default/redgreenblueyellow.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/goldsilvercrystal" --insecure --output tests/resources/moco/Web/album/default/goldsilvercrystal.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/rubysapphireemerald" --insecure --output tests/resources/moco/Web/album/default/rubysapphireemerald.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/fireredleafgreen" --insecure --output tests/resources/moco/Web/album/default/fireredleafgreen.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/diamondpearlplatinium" --insecure --output tests/resources/moco/Web/album/default/diamondpearlplatinium.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/heartgoldsoulsilver" --insecure --output tests/resources/moco/Web/album/default/heartgoldsoulsilver.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/blackwhite" --insecure --output tests/resources/moco/Web/album/default/blackwhite.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/black2white2" --insecure --output tests/resources/moco/Web/album/default/black2white2.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/xy" --insecure --output tests/resources/moco/Web/album/default/xy.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/omegarubyalphasapphire" --insecure --output tests/resources/moco/Web/album/default/omegarubyalphasapphire.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/sunmoon" --insecure --output tests/resources/moco/Web/album/default/sunmoon.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/ultrasunultramoon" --insecure --output tests/resources/moco/Web/album/default/ultrasunultramoon.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/letsgopikachuletsgoeevee" --insecure --output tests/resources/moco/Web/album/default/letsgopikachuletsgoeevee.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/swordshield" --insecure --output tests/resources/moco/Web/album/default/swordshield.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/brilliantdiamondshiningpearl" --insecure --output tests/resources/moco/Web/album/default/brilliantdiamondshiningpearl.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/legendarceus" --insecure --output tests/resources/moco/Web/album/default/legendarceus.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/home" --insecure --output tests/resources/moco/Web/album/default/home.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/homeshiny" --insecure --output tests/resources/moco/Web/album/default/homeshiny.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/homepokemongo" --insecure --output tests/resources/moco/Web/album/default/homepokemongo.json
curl -u web:douze "https://localhost:4431/album/7b52009b64fd0a2a49e6d8a939753077792b0554/alpha" --insecure --output tests/resources/moco/Web/album/default/alpha.json
```
