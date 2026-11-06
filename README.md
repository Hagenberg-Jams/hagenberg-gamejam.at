# Hagenberg Game Jam website

Source of [hagenberg-gamejam.at](https://hagenberg-gamejam.at). The site is built with the [Jam Engine](https://github.com/Hagenberg-Jams/jam-engine), which is shared with the other Hagenberg jams. This repository only holds the content, settings and colours of the Game Jam.

This guide covers everything you need to run the site: the one-time setup, how a change goes live, and what to do when in a jam year.

## 1. Access

- **GitHub:** You need a GitHub account in the `game-jam` team of the [Hagenberg-Jams](https://github.com/Hagenberg-Jams) organisation. The team has admin rights on this repository, so you can push, start workflows and set secrets. The organisation requires two-factor authentication.
- **Registrations:** The admin page at [/admin/registrations](https://hagenberg-gamejam.at/admin/registrations) is protected by Cloudflare Access. You sign in with a one-time code sent to your e-mail address. Ask an organisation owner to add your address to the Access policy "Game Jam Organizers".
- **Game downloads:** Uploading downloads to the file storage needs R2 access keys. Ask an organisation owner for them (section 4, "After the jam").
- **Hosting:** Everything else runs through GitHub Actions. You do not need a Cloudflare account.

## 2. One-time setup

Install these tools:

| Tool | Why | Check with |
| --- | --- | --- |
| [Git](https://git-scm.com) | Download and upload changes | `git --version` |
| PHP 8.2 or newer and [Composer](https://getcomposer.org) | Build the site. [Laravel Herd](https://herd.laravel.com) installs both on Windows and macOS. | `php -v`, `composer -V` |
| [Node.js](https://nodejs.org) 24 LTS | Build styles and scripts | `node -v` |
| [ImageMagick](https://imagemagick.org/script/download.php) 7 | Image commands and responsive images. On Windows, tick "Add application directory to your system path" during installation. | `magick -version` |

Then get the site and start it:

```bash
git clone https://github.com/Hagenberg-Jams/hagenberg-gamejam.at.git
cd hagenberg-gamejam.at
composer install
npm install
npm run build     # builds the styles and scripts
php hyde serve    # opens the site on http://localhost:8080
```

Run `composer install`, `npm install` and `npm run build` again after pulling an engine update.

**With Laravel Herd:** Instead of `php hyde serve`, you can open the site at `http://hagenberg-gamejam.at.test`. Copy `.env.local` to `.env` for that, so that links use this address.

## 3. Making a change

Every push to `main` goes live within about three minutes. Work like this:

1. Get the latest version: `git pull`
2. Make your change. The table below shows where things are.
3. Check it on http://localhost:8080 while `php hyde serve` runs.
4. Check the data: `php hyde jam:validate`
5. Upload it:

   ```bash
   git add -A
   git commit -m "Describe your change"
   git push
   ```

6. Open the **Actions** tab on GitHub. A green tick on the latest **Site** run means the change is live. A red cross means the site was not updated. Open the run to see the error, fix it and push again. The live site stays as it was until then.

**To check a change before it goes live,** push it to a new branch and open a pull request. The checks run and a preview link appears as a comment in the pull request. Merge the pull request to publish it.

**Small text changes** also work on github.com: open the file, click the pencil, and commit to `main`. They go live the same way.

### Where things are

| What | Where |
| --- | --- |
| Home page: hero, highlights, about text, gallery, video, sponsors | `_data/homepage.yaml` |
| Rules | `_data/rules.yaml` |
| Dates and topic release of a jam | `_data/jams/YYYY.yaml` |
| Games of a year | `_data/projects/YYYY.yaml` |
| Imprint | `_data/imprint.yaml` |
| Latest jam, registration, voting, awards, links, contact e-mail | `config/jam.php` |
| Colours | `resources/assets/app.css` |
| Images | `_media/` for the home page, `_media/YYYY/` for a year |
| Game downloads | not in Git, see "After the jam" |

**Dates:** Put dates with a time in quotes and with the time zone, for example `'2026-12-12T09:00:00+01:00'`. Use `+01:00` in winter and `+02:00` in summer.

**Images:** Use WebP where you can. For each image on the home page, the build creates smaller copies ending in `-400w` and `-800w` next to it in `_media/`. Run `php hyde build` once after adding an image and commit these copies too. `php hyde jam:convert-images` converts JPG and PNG files to WebP and updates the YAML files. Try it with `--dry-run` first.

**Sponsors:** Put the logo in `_input/sponsor/`, then run `php hyde jam:add-sponsor`. `php hyde jam:remove-sponsor` removes one.

**Changing the look:** Copy the matching view from `vendor/hagenberg-jams/jam-engine/resources/views/` to the same path in `resources/views/`. Your copy then wins. If it would help other jams too, please suggest it in the [engine repository](https://github.com/Hagenberg-Jams/jam-engine/issues).

## 4. A jam year, step by step

### Months before the jam

1. Create the year:

   ```bash
   php hyde jam:create-jam --keep-latest
   ```

   It asks for the year, the topic release, start, end and duration. Enter the release time, for example `2027-12-10 18:00`, and **do not enter the topic**. `--keep-latest` keeps the last jam in the navigation and on the home page button until the new one has games.

2. Push. The home page now announces the dates and shows a countdown to the topic release.
3. Update `_data/homepage.yaml` if anything changed, for example the about text.

To move the topic release later, run `php hyde jam:topic-release 2027-12-10 18:00` and push. `--year=YYYY` picks another jam, `--remove` turns the countdown off.

### Registration

Teams register on the website at `/register`. The settings are in `config/jam.php` under `registration`:

```php
'registration' => [
    'next_jam' => '2027',
    'active' => false,                    // true opens the registration
    'url' => '',                          // empty: the form on this site
    'deadline' => '2027-12-05T23:59:00',
    'form' => [
        'enabled' => true,
        'team_size' => [1, 6],
        'onsite_capacity' => 100,
        'programs' => [ /* study programmes, each person picks one */ ],
        'texts' => ['intro' => '...', 'confirmed' => '...', 'declined' => ''],
        // ...
    ],
],
```

1. Update `next_jam`, `deadline`, the intro text and the list of study programmes.
2. **To open the registration,** set `'active' => true` and push. The home page then shows the button and the deadline. After the deadline the form closes by itself. `'active' => false` closes it earlier.
3. Each new registration sends a mail to the organisers. Their addresses are in the GitHub secret `JAM_REGISTRATION_NOTIFY`, separated by commas.

**On the admin page** you see all registrations, free seats and the waiting list:

- **Confirm** or **Decline** a team. The team gets a mail unless you untick "Mail the team about changes".
- **Move** a team on site, to remote or to the waiting list. When seats become free, the page suggests the next team from the waiting list. The decision is yours.
- **Delete** works only for declined or cancelled teams, so that every team hears about it first. For a test or a duplicate, untick "Mail the team about changes", decline, then delete.
- **Add a registration** for teams that registered another way. They get a mail with the link to check their details.
- **Exports:** "People on site (CSV)" for the security staff, "Everyone (CSV)", "Teams for the website (JSON)" for adding the games, and "Copy Discord names".

Teams change or cancel their registration with the link in their mail. All registrations are deleted automatically 90 days after the jam.

### At least one week before the topic release

The topic must stay secret until the release time, but this repository and the built pages are public. So the topic never goes into a file. It is stored as a GitHub secret instead:

1. On GitHub, open **Settings → Secrets and variables → Actions** and click **New repository secret**.
2. Add `JAM_TOPIC` with the topic as plain text, for example `Lost in Time`.
3. Optional: add `JAM_TOPIC_VIDEO` with the YouTube ID of a reveal video. That is only the part after `v=`, for example `dQw4w9WgXcQ`. Set the video to unlisted on YouTube until the release.
4. Open **Actions → Site → Run workflow** and run it on `main`.
5. Open the run when it is finished. The step **Transfer the topic to Cloudflare** must show `Success! Uploaded secret JAM_TOPIC_DATA`.

**Without steps 4 and 5, the countdown may reach zero without a topic.** Every later run on `main` also uploads it, including the daily one at 04:00, but only the log shows that it worked. The topic is safe once it is uploaded: the server only hands it out from the release time on.

If the topic changes, update the secret and run the workflow again.

### Reveal day

Nothing to do. At the release time, the countdown turns into the topic on every open page, without reloading. The next build, at the latest the daily one at 04:00, also writes the topic into the page itself.

If the countdown reaches zero and no topic appears, the secret did not reach the server. Check the secret and run the workflow again. Open pages keep asking the server, so the topic appears on them about three minutes later without reloading.

### During the jam

The announcement disappears from the home page when the jam starts, the topic block after the last day of the jam. Use the CSV export for the list of people on site.

### After the jam

1. Add the topic to `_data/jams/YYYY.yaml`, and the video if there was one:

   ```yaml
   topic: 'Lost in Time'
   topic_video: 'dQw4w9WgXcQ'
   ```

2. Push, then delete the secrets `JAM_TOPIC` and `JAM_TOPIC_VIDEO` on GitHub. **Do it in this order,** because without the secret and without `topic:` in the file the topic disappears from the site. Nothing needs to be deleted on Cloudflare: the server ignores a topic from an earlier year.
3. On the admin page, download "Teams for the website (JSON)" and save it as `_input/teams-YYYY.json`. It only holds team names and the names each person chose for the website.
4. Add the games, one at a time. Put the files into `_input/`:

   | Folder | Content |
   | --- | --- |
   | `_input/header/` | One header image, cropped to 1920×520 |
   | `_input/screenshots/` | Screenshots, cropped to 16:9 |
   | `_input/download/` | The ZIP files of the game, any file name |

   Then run:

   ```bash
   php hyde jam:add-project --year=YYYY
   ```

   It offers the teams from the JSON file and asks for the name, players, controls, the description and the award. For each ZIP file it asks for the platform (Windows, Linux, macOS or Web), renames it to `<game>-<Platform>.zip`, moves it to `games/YYYY/` and stores its checksum. The images go to `_media/YYYY/`.
5. Make the new year the latest jam in `config/jam.php`: `'latest_jam' => 'YYYY'`.
6. Upload the downloads to the file storage. The ZIP files are not in Git, the live site serves them from `files.hagenberg-gamejam.at`. Put the R2 access keys into `.env` (see `.env.example`), then:

   ```bash
   php hyde jam:sync-r2 --upload --year=YYYY --dry-run   # shows what would be uploaded
   php hyde jam:sync-r2 --upload --year=YYYY
   ```

   `--download` gets the files of other years from the storage, for example on a new computer.
7. Push. Check that the year page, the game pages and the downloads work.

### Voting and awards

1. For the play session, print a sheet per game: `php hyde jam:print YYYY`. The PDFs land in `storage/pdfs/YYYY/`. `--only=slug1,slug2` prints single games.
2. While the voting runs, set `voting.active` to `true` in `config/jam.php`, with the link and the deadline. The home page shows a button.
3. After the voting, set `voting.active` back to `false` and enter the winners in `_data/projects/YYYY.yaml` with the keys from `awards` in `config/jam.php`, for example `winner: masterminds`.

## 5. Rules

- **Never write the topic into a file in this repository before its release.** The repository is public. The build stops if `topic:` is in a YAML file before `topic_release`.
- Everything on `main` is live. Use a pull request when you are unsure.
- Real names, e-mail addresses and Discord names of registrations never go into this repository. Only the names people chose for the website do.
- Never commit `.env`. It holds the R2 access keys.
- Only add photos you have the rights to, and only of people who agreed to be shown.
- Public addresses must stay as they are: `/YYYY`, `/YYYY/<game>`, `/people`, `/person/<name>`, `/media/...` and the downloads under `/games/YYYY/...`.

## 6. Updates and help

Dependabot checks for updates once a week and opens at most one pull request each for `composer` (PHP packages and the engine), `npm` and `actions` (the shared workflow of the engine). A new engine version comes as two of them, `composer` and `actions`: merge both, so that package and workflow match. Check the preview of each pull request before merging. From one week before the jam until the end of the voting, only merge updates that fix a problem.

`composer test` checks the few PHP files of this repository with Pint and PHPStan. `composer lint:fix` fixes the code style.

Use `php hyde list jam` to see all commands. For problems or ideas that concern the engine, open an issue in the [engine repository](https://github.com/Hagenberg-Jams/jam-engine/issues).

### Settings outside this repository

These are set once and only change when something moves. Organisation owners manage them.

| What | Where |
| --- | --- |
| `CLOUDFLARE_API_TOKEN`, `CLOUDFLARE_ACCOUNT_ID` | GitHub secrets, used to deploy |
| `TURNSTILE_SECRET` | GitHub secret, spam protection of the registration |
| `EMAIL_API_TOKEN` | GitHub secret, mails of the registration |
| `JAM_REGISTRATION_NOTIFY` | GitHub secret, organisers who get a mail per registration |
| `GAMES_BASE_URL` | GitHub variable, `https://files.hagenberg-gamejam.at` |
| Pages project `hagenberg-gamejam-at`, R2 bucket, Turnstile widget, Access app "Game Jam Admin" | Cloudflare dashboard |
