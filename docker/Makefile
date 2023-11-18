LINUX_SHELL ?=/bin/bash
WORKFOLDER=/var/www/html
ENV_FILE = .env
SLEEP = 2
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
.PHONY: list
list:
	$(call help)
.PHONY: help
help:
	$(call help)

artisan:
	$(call artisan)

build:
	$(call build_all)
build/apache:
	$(call build,apache)
build/mysql:
	$(call build,mysql)
build/mailbox:
	$(call build,mailbox)
build/traefik:
	$(call build_traefik)

composer/install:
	$(call composer,install)
composer/update:
	$(call composer,update)
composer/dump-autoload:
	$(call composer,dump-autoload)

down:
	$(call down)
down/traefik:
	$(call downTraefik)

logs/apache:
	$(call container_log,apache)
logs/mysql:
	$(call container_log,mysql)
install:
	$(call install)
mount/apache:
	$(call mount_container,apache)
mount/mysql:
	$(call mount_container,mysql)

restart:
	$(call restart)	

test:
	$(call artisan_w_param,test)

up:
	$(call up)
up/network:
	$(call upNetwork)
up/traefik:
	$(call upTraefik)
uninstall:
	$(call uninstall)



define artisan
	$(call setup_env,$(ENV_FILE))
	docker exec -it -w ${WORKFOLDER} ${PROJECT_NAME}-${CONFIG}-apache php artisan $(RUN_ARGS)
endef

define artisan_w_param
	$(call setup_env,$(ENV_FILE))
	docker exec -it -w ${WORKFOLDER} ${PROJECT_NAME}-${CONFIG}-apache php artisan $(1)
endef

define build
	@set -a
	$(call setup_env,$(ENV_FILE))
	docker-compose --env-file $(ENV_FILE) build --build-arg platform=$(PLATFORM) $(1)
endef

define build_traefik
	@set -a
	$(call setup_env,$(ENV_FILE))
	cd traefik && docker-compose --env-file ../$(ENV_FILE) build --build-arg platform=$(PLATFORM_TRAEFIK) $(1)
endef

define build_all
	$(call build_traefik)
	$(call build,apache)
	$(call build,mysql)
	$(call build,mailbox)
endef

define composer
	$(call setup_env,$(ENV_FILE))
	docker exec -it -w ${WORKFOLDER} ${PROJECT_NAME}-${CONFIG}-apache composer $(1)
endef


define container_log
	$(call setup_env,$(ENV_FILE))
	docker logs -f ${PROJECT_NAME}-${CONFIG}-$(1)
endef

define down
	@set -a
	$(call source $(ENV_FILE))
	@echo === down app ===
	$(call downPlatform)
	@echo === down traefik ===
	$(call downTraefik)
	@echo === list running containers ===
	@docker ps
endef

define downOne
	@set -a
	$(call source $(ENV_FILE))
	cd $(1) && docker compose  --env-file $(ENV_FILE) stop $(2)
endef

define downTraefik
	@set -a
	$(call source ../$(ENV_FILE))
	@cd ./traefik && ./dn 
endef

define downPlatform
	@set -a
	$(call source $(ENV_FILE))
	@docker compose  --env-file $(ENV_FILE) down
endef

define downProfile
	@set -a
	$(call source $(ENV_FILE))
	@COMPOSE_PROFILES=mysql-$(1),common docker compose down
	@echo === list running containers ===
	@docker ps
endef

define install
	@set -a
	@$(call source $(ENV_FILE))
	@echo === setup root/.env file ===
	cp ../root/.env.example ../root/.env
	@echo === generate ssl cert ===
	cd ./traefik && ./certgen
	@echo === copy certs to Apache ===
	@sleep $(SLEEP)
    cp -R traefik/config/ssl ubuntu/apache
	@echo === build images ===
	@sleep $(SLEEP)
	$(call build_all)
	@echo === start containers ===
	@sleep $(SLEEP)
	$(call up)
	@echo === composer install packages ===
	@sleep $(SLEEP)
	$(call composer,install)
	@echo === artisan start migrations  wait 10 sec for database start ===
	@sleep 10 
	$(call artisan_w_param,migrate:fresh)
	@echo === Ready ===
	@echo https://nix.localhost - application url
	@echo http://localhost:1080 - mailbox helper url
endef

define uninstall
	$(call down)
	@set -a
	$(call setup_env,$(ENV_FILE))
	@echo === delete project images ===
	@sleep $(SLEEP)
	cd ./scripts && ./delimages $(PROJECT_NAME)
	@echo === delete project volumes ===
	@sleep $(SLEEP)
	cd ./scripts && ./delvolumes $(PROJECT_NAME)
endef



define mount_container
	$(call setup_env,$(ENV_FILE))
	@docker exec -it -w ${WORKFOLDER} ${PROJECT_NAME}-${CONFIG}-$(1) $(LINUX_SHELL)
endef

define up
	@set -a
	$(call source $(ENV_FILE))
	@echo === launch network ===
	@./scripts/network
	@echo === launch traefik proxy ===
	$(call upTraefik)
	@echo === launch web ===
	$(call upPlatform)
	@echo === list stopped containers ===
	@sleep 1
	@docker ps -f "status=exited"
endef

define upProfile
	@set -a
	$(call source $(ENV_FILE))
	@COMPOSE_PROFILES=mysql-$(1),common docker compose up -d
	@echo === list stopped containers ===
	@docker ps -f "status=exited"
endef



define upOne
	@set -a
	@$(call source $(ENV_FILE))
	@cd $(1) && docker-compose up --no-start $(2)
	@cd $(1) && docker-compose  --env-file $(ENV_FILE)  start $(2)
endef

define upNetwork
	$(call source $(ENV_FILE))
endef

define upTraefik
	@set -a
	$(call source ../$(ENV_FILE))
	cd ./traefik && ./st 
endef

define upPlatform
	@set -a
	$(call source $(ENV_FILE))
	@docker compose  --env-file $(ENV_FILE)  up -d
endef




define setup_env
	$(shell test ! -f $(1) && echo $(ENV_FILE) file does not exist && exit)
    $(eval ENV_FILE := $(1))
    $(eval include $(1))
    $(eval export sed 's/=.*//' $(1))
endef

define restart
	$(call down)
	$(call up)
endef

define import_mysql
	@set -a
	$(call source $(ENV_FILE))
	@docker exec $(join mysql-,$(1)) /bin/sh -c "cat /sql/*.sql | mysql -u root -proot $(join ${PROJECT_NAME},$(shell echo $(1) | tr 'a-z' 'A-Z'))"
endef

define help
	@LC_ALL=C $(MAKE) -pRrq -f $(firstword $(MAKEFILE_LIST)) : 2>/dev/null | awk -v RS= -F: '/(^|\n)# Files(\n|$$)/,/(^|\n)# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | grep -E -v -e '^[^[:alnum:]]' -e '^$@$$'
endef
