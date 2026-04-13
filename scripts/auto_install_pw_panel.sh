#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/var/www/pw-panel"
ENV_SOURCE_FILE=""
WITH_NODE=0
SKIP_MIGRATE=0
FORCE_KEY=0

usage() {
  cat <<'EOF'
Usage: auto_install_pw_panel.sh [options]

Options:
  --project-dir <path>   PW Panel project directory (default: /var/www/pw-panel)
  --env-file <path>      Environment file to copy into .env before install
  --with-node            Install Node dependencies and run production build
  --skip-migrate         Skip php artisan migrate --force
  --force-key            Force regenerate APP_KEY
  -h, --help             Show this help message
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --project-dir)
      PROJECT_DIR="$2"
      shift 2
      ;;
    --env-file)
      ENV_SOURCE_FILE="$2"
      shift 2
      ;;
    --with-node)
      WITH_NODE=1
      shift
      ;;
    --skip-migrate)
      SKIP_MIGRATE=1
      shift
      ;;
    --force-key)
      FORCE_KEY=1
      shift
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown option: $1" >&2
      usage
      exit 1
      ;;
  esac
done

if [[ ! -d "$PROJECT_DIR" ]]; then
  echo "Project directory not found: $PROJECT_DIR" >&2
  exit 1
fi

if ! command -v php >/dev/null 2>&1; then
  echo "php command not found" >&2
  exit 1
fi

if ! command -v composer >/dev/null 2>&1; then
  echo "composer command not found" >&2
  exit 1
fi

cd "$PROJECT_DIR"

if [[ -n "$ENV_SOURCE_FILE" ]]; then
  if [[ ! -f "$ENV_SOURCE_FILE" ]]; then
    echo "Provided env file not found: $ENV_SOURCE_FILE" >&2
    exit 1
  fi
  cp "$ENV_SOURCE_FILE" .env
  echo "Copied env file from: $ENV_SOURCE_FILE"
elif [[ ! -f .env && -f .env.example ]]; then
  cp .env.example .env
  echo "Generated .env from .env.example"
fi

if [[ ! -f .env ]]; then
  echo ".env file is required but not found" >&2
  exit 1
fi

APP_ENV="$(grep -E '^APP_ENV=' .env | head -n1 | cut -d'=' -f2- || true)"

COMPOSER_ARGS=(install --no-interaction --prefer-dist --optimize-autoloader)
if [[ "$APP_ENV" == "production" ]]; then
  COMPOSER_ARGS+=(--no-dev)
fi

composer "${COMPOSER_ARGS[@]}"

mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

CURRENT_APP_KEY="$(grep -E '^APP_KEY=' .env | head -n1 | cut -d'=' -f2- || true)"
if [[ -z "$CURRENT_APP_KEY" || "$FORCE_KEY" -eq 1 ]]; then
  php artisan key:generate --force
  echo "APP_KEY generated"
else
  echo "APP_KEY already set, skip key:generate"
fi

php artisan config:clear
php artisan cache:clear

if [[ "$SKIP_MIGRATE" -eq 0 ]]; then
  php artisan migrate --force
else
  echo "Skip migrate step"
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [[ "$WITH_NODE" -eq 1 ]]; then
  if ! command -v npm >/dev/null 2>&1; then
    echo "npm command not found (required by --with-node)" >&2
    exit 1
  fi

  if [[ -f package-lock.json ]]; then
    npm ci
  else
    npm install
  fi
  npm run build
fi

echo "PW Panel auto install completed successfully"
