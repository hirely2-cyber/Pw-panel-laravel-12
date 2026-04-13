#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/var/www/pw-panel"
ENV_FILE=""
WITH_NODE=1
SKIP_MIGRATE=0
FORCE_KEY=0

usage() {
  cat <<'EOF'
Usage: one_click_install_pw_panel.sh [options]

One-command wrapper for PW Panel installer.

Options:
  --project-dir <path>   PW Panel project directory (default: /var/www/pw-panel)
  --env-file <path>      Path to .env file for target server
  --no-node              Skip npm install/build
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
      ENV_FILE="$2"
      shift 2
      ;;
    --no-node)
      WITH_NODE=0
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

INSTALLER="$PROJECT_DIR/scripts/auto_install_pw_panel.sh"

if [[ ! -x "$INSTALLER" ]]; then
  chmod +x "$INSTALLER"
fi

CMD=("$INSTALLER" --project-dir "$PROJECT_DIR")

if [[ -n "$ENV_FILE" ]]; then
  CMD+=(--env-file "$ENV_FILE")
fi

if [[ "$WITH_NODE" -eq 1 ]]; then
  CMD+=(--with-node)
fi

if [[ "$SKIP_MIGRATE" -eq 1 ]]; then
  CMD+=(--skip-migrate)
fi

if [[ "$FORCE_KEY" -eq 1 ]]; then
  CMD+=(--force-key)
fi

"${CMD[@]}"

echo "One-click install finished"