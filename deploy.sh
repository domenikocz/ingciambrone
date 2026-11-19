#!/bin/bash
# Deploy upload-only verso Aruba: carica/sovrascrive SOLO i file del sito
# generato in _site/. Non cancella mai nulla sul server (regola permanente).
set -euo pipefail
cd "$(dirname "$0")"

CREDS_FILE="/root/.secrets/aruba_ftp.env"
if [ ! -f "$CREDS_FILE" ]; then
  echo "Credenziali FTP non trovate in $CREDS_FILE" >&2
  exit 1
fi
source "$CREDS_FILE"

FTP_HOST="ftplnx02.aruba.it"  # nome del certificato TLS Aruba (ftp.dskshop.it punta allo stesso server)
REMOTE_BASE="www.ingciambrone.it"

echo "== Build sito =="
npm run build

echo "== Upload su ${FTP_HOST}/${REMOTE_BASE} (solo file presenti in _site/, nessuna cancellazione) =="
cd _site
# Credenziali passate a curl via stdin (-K -), non negli argomenti: non visibili in ps.
# Escape di \ e " richiesto dal formato di configurazione di curl.
CURL_USER="${FTP_USER}:${FTP_PASS}"
CURL_USER="${CURL_USER//\\/\\\\}"
CURL_USER="${CURL_USER//\"/\\\"}"
find . -type f | while read -r f; do
  rel="${f#./}"
  echo "  -> $rel"
  curl -sS --ssl-reqd --ftp-create-dirs -K - \
    -T "$rel" \
    "ftp://${FTP_HOST}/${REMOTE_BASE}/${rel}" <<< "user = \"${CURL_USER}\""
done

echo "== Deploy completato =="
