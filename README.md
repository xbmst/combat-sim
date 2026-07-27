## Hero Combat Simulator

### How to run:

Populate `.env` (or .env.local)

```dotenv
# example
POSTGRES_DB=app_db
POSTGRES_PASSWORD=app_pass
POSTGRES_USER=app_user
```

From project root run one
```shell
docker compose --env-file .env --env-file .env.local up -d
# or
# make dev
```

`make dev` automatically loads `.env.local` after `.env` when file exists.

### API doc:

```
http://.../api/doc

# default:
http://localhost:8080/api/doc
```

### How to play:

- Run `/api/games/setup-data` to get a Classes and Items list
- Run `/api/games/start` to receive a `gameId`
- Run `/api/games/{gameId}/next-round` to play next round

### Static analysis:

PHPStan analyses `src` using the configuration in `phpstan.dist.neon`.

```shell
# Run analyze
make pstan

# Pass additional PHPStan arguments after `--`:
make pstan -- --level 8


# Regenerate the baseline:
make pstan-baseline
```

###### OPCache in dev:

To disable OPCache in Dev environment replace `docker/php/opcache.ini` with:

```ini
opcache.enable=0

opcache.jit=disable
opcache.jit_buffer_size=0
```


###### Makefile:
```shell
make help
make [target] -- [arguments] # e.g. make down -- -v
```
