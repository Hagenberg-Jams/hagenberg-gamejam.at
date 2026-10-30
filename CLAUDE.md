# Hagenberg Game Jam website

Static site for https://hagenberg-gamejam.at, built with HydePHP (Laravel Zero) and deployed to Cloudflare Pages. See `README.md` for the full content workflow, `LINTING.md` for tooling and `UPGRADE.md` for dependency upgrades.

## Commands

```bash
composer install && npm install   # setup
php hyde serve                    # dev server (or open the Herd domain)
php hyde build                    # build the site into _site/
npm run build                     # rebuild _media/app.css and _media/app.js
composer test                     # Pint check + PHPStan level 9
composer lint:fix                 # apply Pint formatting
php hyde list gamejam             # content commands (create-jam, add-game, print, sync-r2, ...)
```

## Architecture

The site is data-driven:

1. `_data/jams/YYYY.yaml` holds jam metadata and `_data/games/gamesYYYY.yaml` holds its games. `_data/homepage.yaml` and `_data/rules.yaml` hold page content.
2. `app/GameJamData.php` loads the YAML. `app/Actions/GenerateGameJamPagesBuildTask.php` registers in-memory pages for years, games, people and persons. Do not create `_pages/` files for these.
3. Views in `resources/views/` render the pages. `_pages/` holds the home, rules, imprint and 404 pages.
4. `config/gamejam.php` holds site settings: latest jam, registration, voting, branding and links.

Images live in `_media/YYYY/`. `_input/` is a temporary drop folder for `gamejam:add-game`.

## Things to watch

- Pushing to `main` deploys to production immediately via `.github/workflows/deploy.yml`.
- CI does not run npm. If you add Tailwind classes or change `resources/assets/`, run `npm run build` and commit `_media/app.css` and `_media/app.js`.
- Download ZIPs in `games/` are not in Git. Production serves them from Cloudflare R2 through `GAMES_BASE_URL`, synced with `gamejam:sync-r2`.
- There are no automated tests. `composer test` only covers style and static analysis, so check rendered pages after changing views or build tasks.
- Public URLs must stay stable: `/YYYY`, `/YYYY/<slug>`, `/people`, `/person/<slug>`, `/media/...` and `/games/YYYY/...`.
- Image commands need ImageMagick (`magick`) on the PATH.
- Never commit `.env` or R2 credentials.

## Conventions

- PHPStan runs at level 9. Add real types rather than extending `phpstan-baseline.neon`.
- Code, comments, site copy and docs are in English.

## Commits

Commit only under the maintainer's Git identity. Never set Claude as author and never add a `Co-Authored-By` trailer.
