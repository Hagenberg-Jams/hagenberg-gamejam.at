# Hagenberg Game Jam website

Static site for https://hagenberg-gamejam.at, built with the Jam Engine (`vendor/hagenberg-jams/jam-engine`, HydePHP) and deployed to Cloudflare Pages. See `README.md` for the content workflow.

## Commands

```bash
composer install && npm install
npm run build             # builds _media/app.css and _media/app.js, which are not in Git
php hyde serve            # dev server
php hyde build            # builds the site into _site/
php hyde jam:validate     # checks the data
php hyde jam:check-links  # checks internal links of the built site
composer test             # Pint and PHPStan for the few PHP files in app/
php hyde list jam         # content commands (create-jam, add-project, sync-r2, print, ...)
```

## Architecture

- Pages, views, build tasks and commands come from the engine. This repository holds content and settings only.
- `_data/jams/YYYY.yaml` holds jam metadata, `_data/projects/YYYY.yaml` its games. `_data/homepage.yaml`, `_data/rules.yaml` and `_data/imprint.yaml` hold page content.
- `config/jam.php` holds the site settings: latest jam, registration, voting, awards, branding and links. Everything else comes from the engine defaults and the `game` preset.
- A view copied to `resources/views/` overrides the engine's view of the same path.
- `.github/workflows/site.yml` calls the reusable workflow of the engine. It builds, checks, deploys, sets up the registration database and uploads the topic secret.

## Rules

- Shared behaviour belongs in the engine: https://github.com/Hagenberg-Jams/jam-engine
- Never write a jam's topic into this public repository before its `topic_release`. It belongs in the `JAM_TOPIC` GitHub secret until then.
- Pushing to `main` deploys to production immediately. Use a pull request for a preview.
- Public URLs must stay stable: `/YYYY`, `/YYYY/<slug>`, `/people`, `/person/<slug>`, `/media/...` and `/games/YYYY/...`.
- Download ZIPs in `games/` are not in Git. Production serves them from Cloudflare R2 through `GAMES_BASE_URL`, synced with `jam:sync-r2`.
- Registration data (real names, e-mail, Discord) stays in Cloudflare D1. Only `_input/teams-YYYY.json` with chosen names is used locally, and `_input/` is not committed.
- Never commit `.env` or R2 credentials.
- Quote dates with a time in YAML, for example `'2026-12-12T09:00:00+01:00'`.
- Image commands need ImageMagick (`magick`) on the PATH.
- Code, comments, site copy and docs are in English.

## Commits

Commit only under the maintainer's Git identity. Never set Claude as author and never add a `Co-Authored-By` trailer.
