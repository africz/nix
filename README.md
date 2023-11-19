# Get the source code
git clone git@github.com:africz/nix.git

# Set Docker environment
cd nix/docker
edit .env

# MAC installation
PLATFORM=arm64v8 # amd64 for Linux | arm64v8 | for M2, M1
PLATFORM_TRAEFIK=arm64 # amd64 for Linux | arm64 | for M2, M1
PROJECT_PATH=/Volumes/projects/nix

# Linux installation
PLATFORM=amd64 # amd64 for Linux | arm64v8 | for M2, M1
PLATFORM_TRAEFIK=amd64 # amd64 for Linux | arm64 | for M2, M1
PROJECT_PATH=/projects/nix

# Generate ssl certificate
cd traefik
./certgen

# Set project environment
cd ../../root
cp .env.example .env
# Install application 
cd ../docker
make install

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

make test

# Docker structure

- traefik        - load balancer provide named host like nix.localhost
                   and easy to switch https/http in case of needs
- apache and php - Apache, PHP8.1, Laravel 10
- mysql          - version 8 
- mailbox        - local smtp, mailbox ideal for development 

# Most used make commands:

make up
make down
make mount/apache
make artisan
make artisan migrate:fresh
make composer/update 
make logs/apache
make test
make build
make build/apache
make uninstall (remove project docker images, volumes)

make help to see all of them

# Install steps on screen shoots
![image](https://user-images.githubusercontent.com/5225210/3a96b771-bb4c-4835-a893-2c1223c8658a)
![image](https://github.com/africz/nix/assets/5225210/3a96b771-bb4c-4835-a893-2c1223c8658a)
![image](https://github.com/africz/nix/assets/5225210/2675b35f-4bda-4687-9682-8c65326ec47f)
![image](https://github.com/africz/nix/assets/5225210/5876f50e-0c16-471a-9efa-84e6ea8c8a25)
![image](https://github.com/africz/nix/assets/5225210/a26f0ae8-1b4d-4bc9-b0db-aeb1cd7de5d2)
![image](https://github.com/africz/nix/assets/5225210/e589138c-e89d-4f54-abcf-91fe9b857d1c)
![image](https://github.com/africz/nix/assets/5225210/ba88f691-45bb-43a2-8138-304755f7bc1a)
![image](https://github.com/africz/nix/assets/5225210/6d5c33a0-8ab4-466e-9b3c-b4ab7b6a24e8)
![image](https://github.com/africz/nix/assets/5225210/7a6a1e6a-47e6-4a31-a9a5-c8eb57ed00c1)
![image](https://github.com/africz/nix/assets/5225210/e80bc455-e64f-4949-9f6f-d6734359468d)

Go to mailbox to verify registration
http://localhost:1080
![image](https://github.com/africz/nix/assets/5225210/82bb13d5-74bf-4645-8e08-412f1fbb63c2)

After verify button pressed in email
![image](https://github.com/africz/nix/assets/5225210/35b99e5f-aae3-4662-8f03-eceba9d9a42d)

Run tests
![image](https://github.com/africz/nix/assets/5225210/59a51f27-bb93-4d5d-8fd6-184f43d5ff2c)


I used a github repo, to make validation test faster instead of write test of each validation 
I used this repo to test validation rules in one block.

https://github.com/jasonmccreary/laravel-test-assertions



