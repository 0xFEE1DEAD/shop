
# Shop
Application of an online shop, test task

## Endpoints
* **category/** - return category and products as tree view in html
*  **category/change/order/up/{category}** - change order up for category parameters: *by - required|intger|min:1*
* **category/change/order/down/{category}** - change order down for category parameters: *by - required|intger|min:1*
* **order/report** - generate report file parameters: *type - required|in:csv,json*
* **product/change/order/up/{product}** - change order up for productparameters: *by - required|intger|min:1*
* **product/change/order/down/{product}** - change order down for productparameters: *by - required|intger|min:1*

## Tests start
```bash
php artisan test
```
## Start app
* You can start app use DevContainer extension for visual studio code.
* Or use docker compose
```bash
docker compose -f .devcontainer/docker-compose.yml up -d
```
**Warning you must change USERID and GROUPID in .devcontainer/docker-compose.yml to the same as your local user or root**
```yml
USERID: 197610
GROUPID: 197121
```
You can knows userid and groupid by command
```bash
id
```

For installing app
```bash
docker exec -u 197610 -it devcontainer-nginx-unit-1 /bin/bash
composer install
echo 'APP_KEY=' > .env
php artisan key:generate
php artisan migrate
php artisan test
php artisan db:seed
```
App will become available on port 8888
