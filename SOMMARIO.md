# Sommario — ingciambrone

**Scopo:** Sito blog statico ingciambrone.it — ingegneria energetica e fotovoltaico, generato con Eleventy.
**Stack:** Node.js + Eleventy (11ty) 2.x.
**Entry point:** `npm run build` (genera `_site/`), `npm run serve` (dev server), `deploy.sh` per il deploy.
**Porta/servizio:** nessun server applicativo persistente — output statico servito da nginx/altro.
**Struttura principale:**
- `src/`: contenuti/sorgenti del sito (articoli, template, asset).
- `.eleventy.js`: configurazione build Eleventy.
- `_site/`: output generato (build statica).
- `setup/`: script di supporto al setup.
- `deploy.sh`: script di pubblicazione.

**Note:** Stessa struttura gemella di `dskshop` (entrambi siti Eleventy per domini diversi).

_Sommario generato automaticamente il 2026-11-01 — se il progetto cambia molto, rigeneralo._
