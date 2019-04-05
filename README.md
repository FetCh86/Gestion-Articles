# Gestion-Article

###### Requirements :

Docker<br>
Docker-compose<br>

###### How to start :

```
cp .env.dist .env

docker-compose up -d
docker-compose exec web composer install
```

Open a web-browser and type 'localhost:9000'. (Make sure there is nothing listening on port *9000 to avoid conflits)


### More :

Create an admin by php bin/console with :
````
commande            params
app:create-admin    (pseudo) (email) (password)
````

Access to the database at :
(id -> root | password -> root)
```
http://localhost:8080 
```

Turn application from DEV environnement to PROD environnement :
```
#in file .env
[...]
APP_ENV=prod
[...]
```

