<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Prompts\confirm;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;

class GitHelperCommand extends Command
{
    protected $signature = 'git:helper';

    protected $description = 'Interactive Git helper for staging, committing, and pushing changes with linting and tests';

    public function handle()
    {
        // Step 1: Ask if the user wants to run Pint
        if (confirm('Do you want to run Laravel Pint for code style checks?', default: true)) {
            if (! $this->isPintInstalled()) {
                if (confirm('Laravel Pint is not installed. Do you want to install it as a dev dependency?', default: true)) {
                    $this->installPint();
                }
            }
            $this->runPint();
        }

        // Step 2: Ask if the user wants to run PHPUnit tests
        if (confirm('Do you want to run PHPUnit tests?', default: true)) {
            if (! $this->isPhpunitInstalled()) {
                if (confirm('PHPUnit is not installed. Do you want to install it as a dev dependency?', default: true)) {
                    $this->installPhpunit();
                }
            }
            $this->runPhpunit();
        }

        // Step 3: Ask if the user wants to run PHPStan analysis
        if (confirm('Do you want to run PHPStan for static analysis?', default: true)) {
            if (! $this->isPhpstanInstalled()) {
                if (confirm('PHPStan is not installed. Do you want to install it as a dev dependency?', default: true)) {
                    $this->installPhpstan();
                }
            }
            $this->runPhpstan();
        }

        // Step 4: Select a Git branch
        //        $branch = multisearch(
        //            label: 'Select the branch to switch to',
        //            options: fn () => $this->getGitBranches(), // Use a closure to provide options
        //            required: true
        //        );
        //        $this->info("Switching to branch: $branch");

        // Step 5: Show current changes and select files to stage
        $changes = $this->getGitChanges();
        $files = multisearch(
            label: 'Select files to stage (space to select, enter to confirm)',
            options: fn () => array_merge(['Select All'], $changes), // Add "Select All" option
            required: true
        );

        // Check if "Select All" was chosen
        if (in_array('Select All', $files)) {
            $files = $changes; // Stage all files
            $this->info('Staging all files...');
        } else {
            $this->info('Staging selected files: '.implode(', ', $files));
        }

        // Proceed with staging files
        $this->stageFiles($files);

        // Step 6: Show differences for review
        $this->info('Showing differences:');
        $diff = $this->getGitDiff();
        $this->table(
            ['File', 'Diff'],
            $diff
        );

        // Step 7: Confirm commit message
        $commitMessage = $this->ask('Enter commit message');
        if (confirm('Do you want to proceed with the commit?', default: true)) {
            // Commit changes
            spin(
                callback: fn () => $this->commitChanges($commitMessage),
                message: 'Committing changes...'
            );
            $this->info('Changes committed successfully.');

            // Step 8: Push changes
            if (confirm('Do you want to push the changes?', default: true)) {
                spin(
                    callback: fn () => $this->pushChanges(),
                    message: 'Pushing changes...'
                );
                $this->info('Changes pushed successfully.');
            }
        }

        outro('Git operations completed.');
    }

    private function isPintInstalled(): bool
    {
        return shell_exec('composer show | grep "laravel/pint"') !== null;
    }

    private function installPint(): void
    {
        shell_exec('composer require laravel/pint --dev');
        $this->info('Laravel Pint installed.');
    }

    private function runPint(): void
    {
        spin(
            callback: fn () => shell_exec('vendor/bin/pint'),
            message: 'Running Laravel Pint...'
        );
    }

    private function isPhpunitInstalled(): bool
    {
        return shell_exec('composer show | grep "phpunit/phpunit"') !== null;
    }

    private function installPhpunit(): void
    {
        shell_exec('composer require phpunit/phpunit --dev');
        $this->info('PHPUnit installed.');
    }

    private function runPhpunit(): void
    {
        spin(
            callback: fn () => shell_exec('vendor/bin/phpunit'),
            message: 'Running PHPUnit tests...'
        );
    }

    private function isPhpstanInstalled(): bool
    {
        return shell_exec('composer show  | grep "phpstan/phpstan"') !== null;
    }

    private function installPhpstan(): void
    {
        shell_exec('composer require phpstan/phpstan --dev');
        $this->info('PHPStan installed.');
    }

    private function runPhpstan(): void
    {
        spin(
            callback: fn () => shell_exec('vendor/bin/phpstan analyze'),
            message: 'Running PHPStan...'
        );
    }

    private function getGitBranches(): array
    {
        $output = shell_exec('git branch -a');
        $branches = array_filter(explode("\n", $output));

        return array_combine($branches, $branches);
    }

    private function getGitChanges(): array
    {
        $output = shell_exec('git status --porcelain');
        $lines = array_filter(explode("\n", $output));

        return array_map(fn ($line) => trim(substr($line, 3)), $lines);
    }

    private function getGitDiff(): array
    {
        $output = shell_exec('git diff --name-status');
        $lines = array_filter(explode("\n", $output));

        return array_map(fn ($line) => explode("\t", $line), $lines);
    }

    private function commitChanges(string $message): void
    {
        shell_exec("git commit -m \"$message\"");
    }

    private function pushChanges(): void
    {
        shell_exec('git push');
    }

    private function stageFiles(array $files): void
    {
        $filesList = implode(' ', array_map('escapeshellarg', $files));
        shell_exec("git add $filesList");
    }
}
