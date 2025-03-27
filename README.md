# Unit tests beginner course
## Project configuration

Congratulations on successfully downloading the course code!

### 1. Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start your local environment
4. Run `docker compose down --remove-orphans` to stop the Docker containers.


### 2. Database Setup

```
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load
```

### 3. Access the site

Visit the site at https://localhost and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)

You're all set ! Now follow the course and have a great time!
