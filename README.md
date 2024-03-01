# iCal Events

The purpose of this application is to retrieve events from the iCal file and then return event details in the following form:
-   "id": <string>,
-   "start": <date> (format: YYYY-MM-DD),
-   "end": <date> (format: YYYY-MM-DD),
-   "summary": <string>

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Clone this repository locally `git clone https://github.com/pwachnicka/iCal-Events.git`
3. Save `.env.dist` as `.env` and fill `ICAL_FILE_PATH` variable with iCal file URL
4. Run `docker compose build --no-cache` to build fresh images
5. Run `docker compose up --pull always -d --wait` to start the project
6. Open `https://localhost/api/events` in your favorite web browser - you should be able to see all events from iCal file
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Run CLI command

To run CLI command, you can either SSH into Docker container or install all dependencies using `composer install`. Then, just enter following command:

```sh
php bin/console app:get-events-detail
```

## Run unit test

Same as CLI command, you can either SSH into Docker or use local environment. Use `php bin/phpunit` to run unit tests.

## Project assumptions

If the supplied iCal file is invalid, the application will return an empty array.