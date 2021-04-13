## Set up
1. Copy and modify .env according to your needs
    ```bash
    $ cp .env.dist .env
    ```
2. Build docker containers:
    ```bash
    $ docker-compose up -d --build
    ```
3. Update your system host file (add btcmarket.loc)
    ```bash
    # UNIX: Get container IP address and update host (replace IP according to your configuration) (on Windows, edit C:\Windows\System32\drivers\etc\hosts)
    $ sudo sh -c 'echo $(docker network inspect bridge | grep Gateway | grep -o -E '[0-9\.]+') "btcmarket.loc" >> /etc/hosts'
    ```
4. Prepare Symfony app
    ```bash
    $ docker exec -u 1000 -it php-fpm bash
    # Composer
    $ composer install
    # Add fixtures
    $ bin/console doctrine:mongodb:fixtures:load --no-interaction
    ```
## Usage
Use the following command to fetch the latest trade results (currently for 10 days according to the market API restrictions):
```bash
$ bin/console app:fetch-history
```
This command can append the latest history results from market to the project database.

Endpoint to filter trade history results (use UTC timezone for dates):
```http request
GET http://btcmarket.loc/history/BTCUSD?dateStart=2021-04-12T01:00:00&dateEnd=2021-04-13T12:00:00
```
Response
```json
[
   {
      "date":"2021-04-12T01:00:00+00:00",
      "price":59908.33
   },
   {
      "date":"2021-04-12T02:00:00+00:00",
      "price":59781.6
   }
]
```
## BitcoinAverage API
[Documentation](https://apiv2.bitcoinaverage.com/#history-data-since-timestamp)

