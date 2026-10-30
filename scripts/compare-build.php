<?php

/**
 * Compares the built site of the current checkout with a baseline.
 *
 * Usage:
 *   php scripts/compare-build.php [git-ref]         Builds the ref (default: pre-engine) in a temp clone
 *   php scripts/compare-build.php --site=<dir>      Uses an existing _site directory as the baseline
 *
 * Uses the same environment as the production build, and exits with 1 if the outputs differ.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

foreach ([
    'GAMES_BASE_URL' => 'https://files.hagenberg-gamejam.at',
    'SITE_URL' => 'https://hagenberg-gamejam.at',
    'GAMEJAM_WEBSITE' => 'https://hagenberg-gamejam.at',
] as $name => $value) {
    putenv("{$name}={$value}");
}

function run(string $command): void
{
    passthru($command, $code);
    if ($code !== 0) {
        fwrite(STDERR, "Command failed: {$command}\n");
        exit(2);
    }
}

function build(): void
{
    run('php -d display_errors=0 hyde build --no-interaction');
}

/** @return array<string, string> Relative path => absolute path */
function listFiles(string $dir): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        /** @var SplFileInfo $file */
        if ($file->isFile()) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($dir) + 1));
            $files[$relative] = $file->getPathname();
        }
    }
    ksort($files);

    return $files;
}

function normalize(string $relative, string $content): string
{
    // The sitemap contains the build time.
    if ($relative === 'sitemap.xml') {
        $content = (string) preg_replace('#<lastmod>[^<]*</lastmod>#', '<lastmod/>', $content);
        $content = (string) preg_replace('#processing_time_ms="[^"]*"#', '', $content);
    }

    return $content;
}

$baseline = null;
$reference = 'pre-engine';
foreach (array_slice($argv, 1) as $argument) {
    if (str_starts_with($argument, '--site=')) {
        $baseline = rtrim(substr($argument, 7), '/\\');
    } else {
        $reference = $argument;
    }
}

if ($baseline === null) {
    $clone = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'jam-baseline-' . uniqid();
    run('git clone -q . ' . escapeshellarg($clone));
    chdir($clone);
    run('git checkout -q ' . escapeshellarg($reference));
    run('composer install --no-dev --no-interaction --no-progress');
    build();
    $baseline = $clone . DIRECTORY_SEPARATOR . '_site';
    chdir($root);
}

build();

$current = $root . DIRECTORY_SEPARATOR . '_site';
$old = listFiles($baseline);
$new = listFiles($current);

$missing = array_keys(array_diff_key($old, $new));
$added = array_keys(array_diff_key($new, $old));
$changed = [];

foreach (array_intersect_key($old, $new) as $relative => $path) {
    $before = normalize($relative, (string) file_get_contents($path));
    $after = normalize($relative, (string) file_get_contents($new[$relative]));
    if ($before !== $after) {
        $changed[] = $relative;
    }
}

foreach (['Missing' => $missing, 'Added' => $added, 'Changed' => $changed] as $label => $paths) {
    foreach ($paths as $path) {
        echo "{$label}: {$path}\n";
    }
}

printf(
    "\nCompared %d baseline files: %d missing, %d added, %d changed.\n",
    count($old),
    count($missing),
    count($added),
    count($changed),
);

exit($missing === [] && $added === [] && $changed === [] ? 0 : 1);
