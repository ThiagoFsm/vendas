#!/bin/bash
# Garante que o container node está ativo e com a porta 5173 mapeada
docker compose up -d node

# Encerra qualquer processo anterior do Vite que tenha ficado preso no container
docker compose exec node pkill -f vite 2>/dev/null || true

# Executa o Vite com Hot Module Replacement (HMR) e reload automático
docker compose exec node npm run dev
