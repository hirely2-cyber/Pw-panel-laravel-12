#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/var/www/pw-panel"
OUTPUT_DIR=""
INCLUDE_DB=1

usage() {
  cat <<'EOF'
Usage: backup_pw_panel.sh [options]

Options:
  --project-dir <path>   PW Panel project directory (default: /var/www/pw-panel)
  --output-dir <path>    Output directory for backup files (default: <project-dir>/backups)
  --no-db                Skip MySQL database dump
  -h, --help             Show this help message
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --project-dir)
      PROJECT_DIR="$2"
      shift 2
      ;;
    --output-dir)
      OUTPUT_DIR="$2"
      shift 2
      ;;
    --no-db)
      INCLUDE_DB=0
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

if [[ -z "$OUTPUT_DIR" ]]; then
  OUTPUT_DIR="$PROJECT_DIR/backups"
fi

mkdir -p "$OUTPUT_DIR"

TS="$(date +%Y%m%d_%H%M%S)"
ARCHIVE_FILE="$OUTPUT_DIR/pw-panel_backup_${TS}.tar.gz"

# Source backup excludes heavy/generated directories that can be rebuilt.
tar -C "$PROJECT_DIR" \
  --exclude='./backups' \
  --exclude='./node_modules' \
  --exclude='./vendor' \
  --exclude='./storage/logs/*.log' \
  -czf "$ARCHIVE_FILE" .

echo "Source backup created: $ARCHIVE_FILE"

if [[ "$INCLUDE_DB" -eq 1 ]]; then
  ENV_FILE="$PROJECT_DIR/.env"

  if [[ ! -f "$ENV_FILE" ]]; then
    echo "Skip DB dump: .env file not found at $ENV_FILE"
    exit 0
  fi

  DB_CONNECTION="$(grep -E '^DB_CONNECTION=' "$ENV_FILE" | head -n1 | cut -d'=' -f2- || true)"
  DB_HOST="$(grep -E '^DB_HOST=' "$ENV_FILE" | head -n1 | cut -d'=' -f2- || true)"
  DB_PORT="$(grep -E '^DB_PORT=' "$ENV_FILE" | head -n1 | cut -d'=' -f2- || true)"
  DB_DATABASE="$(grep -E '^DB_DATABASE=' "$ENV_FILE" | head -n1 | cut -d'=' -f2- || true)"
  DB_USERNAME="$(grep -E '^DB_USERNAME=' "$ENV_FILE" | head -n1 | cut -d'=' -f2- || true)"
  DB_PASSWORD="$(grep -E '^DB_PASSWORD=' "$ENV_FILE" | head -n1 | cut -d'=' -f2- || true)"

  DB_HOST="${DB_HOST:-127.0.0.1}"
  DB_PORT="${DB_PORT:-3306}"

  if [[ "$DB_CONNECTION" != "mysql" ]]; then
    echo "Skip DB dump: DB_CONNECTION is not mysql (got: ${DB_CONNECTION:-empty})"
    exit 0
  fi

  if [[ -z "$DB_DATABASE" || -z "$DB_USERNAME" ]]; then
    echo "Skip DB dump: DB_DATABASE or DB_USERNAME is empty"
    exit 0
  fi

  if ! command -v mysqldump >/dev/null 2>&1; then
    echo "Skip DB dump: mysqldump command is not available"
    exit 0
  fi

  SQL_FILE="$OUTPUT_DIR/pw-panel_db_${TS}.sql"
  GZ_SQL_FILE="${SQL_FILE}.gz"

  MYSQL_PWD="$DB_PASSWORD" mysqldump \
    --single-transaction \
    --routines \
    --triggers \
    -h "$DB_HOST" \
    -P "$DB_PORT" \
    -u "$DB_USERNAME" \
    "$DB_DATABASE" > "$SQL_FILE"

  gzip -f "$SQL_FILE"
  echo "Database backup created: $GZ_SQL_FILE"
fi
