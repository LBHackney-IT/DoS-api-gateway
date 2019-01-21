# Directory of Services API Gateway

A microservice to provide an API gateway to the Hackney Directory of Services system.  

Full documentation can be found in the system documentation microsite at https://docs.hc-dos.co.uk/.

Runs in Docker.

## Installation

Installation assumes you have Docker running.

Copy `.env.example` to `.env` for and adapt as required, e.g. for local development, or set system environment variables.

```bash
$ make up
```

### Containers

- **php** uses PHP 7.2 image from [convivio/php-kafka](https://hub.docker.com/r/convivio/php-kafka/)
- **nginx** uses Nginx 1.15 image [wodby/nginx](https://hub.docker.com/r/wodby/nginx/)
- **mailhog** to catch local mail
- **redis** for local data storage
- **traefik**

### Composer dependencies and gotchas

The system has a number of dependency libraries and packages that must be installed.

A key package supports PHP integration with Apache Kafka, and to install this dependency checks that the PHP [Rdkafka](https://github.com/arnaud-lb/php-rdkafka/) extension is installed. Since this is unlikely to be installed on a host or CI/CD system, the composer install process will (probably) need to run on the PHP Docker container.


## API docs

API specification documentation is in the [docs/api](./docs/api) directory.
