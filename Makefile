.PHONY: up down restart bash test test-parallel stan rector rector-dry pint pint-test qa check dev build npm-install

# ==========================
# Frontend & Vite (Hot Reload)
# ==========================
dev:
	docker compose exec node npm run dev

build:
	docker compose exec node npm run build

npm-install:
	docker compose exec node npm install

# ==========================
# Docker & Ambiente
# ==========================
up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

bash:
	docker compose exec app bash

# ==========================
# Testes
# ==========================
test:
	docker compose exec app php artisan test

test-parallel:
	docker compose exec app php artisan test --parallel

# ==========================
# Análise Estática (Larastan / PHPStan)
# ==========================
stan:
	docker compose exec app ./vendor/bin/phpstan analyse --memory-limit=1G

# ==========================
# Refatoração & Upgrade (Rector)
# ==========================
rector:
	docker compose exec app ./vendor/bin/rector process

rector-dry:
	docker compose exec app ./vendor/bin/rector process --dry-run

# ==========================
# Padrões de Código (Laravel Pint)
# ==========================
pint:
	docker compose exec app ./vendor/bin/pint

pint-test:
	docker compose exec app ./vendor/bin/pint --test

# ==========================
# Pipeline de Qualidade Completo (QA)
# ==========================
qa: pint-test stan test-parallel
check: qa
