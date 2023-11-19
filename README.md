# Get the source code
```
git clone git@github.com:africz/nix.git
```
# Set Docker environment
```
cd nix/docker
vi .env
```
# MAC installation
```
PLATFORM=arm64v8 # amd64 for Linux | arm64v8 | for M2, M1
PLATFORM_TRAEFIK=arm64 # amd64 for Linux | arm64 | for M2, M1
PROJECT_PATH=/Volumes/projects/nix
```
# Linux installation
```
PLATFORM=amd64 # amd64 for Linux | arm64v8 | for M2, M1
PLATFORM_TRAEFIK=amd64 # amd64 for Linux | arm64 | for M2, M1
PROJECT_PATH=/projects/nix
```
# Generate ssl certificate
```
cd traefik
./certgen
```
# Set project environment
```
cd ../../root
cp .env.example .env
```
# Install application 
```
cd ../docker
make install
```
Start application 
Visit https://nix.localhost

Mailbox to open verification emails
http://localhost:1080

# Form protections
- https
- input XSS prevention middleware
- prepared statements at DB level
- input validation 
- strong password
- csrf 
- email verification
- flooding protection with throttle 

In a real word application we can use more such as:
- captcha 
- Laravel Fortify 2FA authentication with Google Authentication
- SMS verification
- Okta platform authentication
- force to use https 
  (in dev environment I like to switch easy between http and https so no force applied here)
- CSP policy
- regular security analysis SonarQube as an example 


# Run tests
```
make test
```
# Docker structure

- traefik        - load balancer provide named host like nix.localhost
                   and easy to switch https/http in case of needs
- apache and php - Apache, PHP8.1, Laravel 10
- mysql          - version 8 
- mailbox        - local smtp, mailbox ideal for development 

# Most used make commands:

- make up
- make down
- make mount/apache
- make artisan
- make artisan migrate:fresh
- make composer/update 
- make logs/apache
- make test
- make build
- make build/apache
- make uninstall (remove project docker images, volumes)

- make help to see all of them

I used a github repo, to make validation test faster instead of write test of each validation 
I used this repo to test validation rules in one block.

https://github.com/jasonmccreary/laravel-test-assertions



