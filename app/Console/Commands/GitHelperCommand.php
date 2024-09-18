<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class GitHelperCommand extends Command
{
    protected $signature = 'git:helper';

    protected $description = 'Helper command for running Pint, PHPStan, PHPUnit, and managing Git operations.';

    public function handle()
    {
        // Step 1: Ensure Laravel Pint is installed
        $this->ensurePintInstalled();

        // Step 2: Ensure PHPStan is installed
        $this->ensurePHPStanInstalled();

        // Step 3: Ensure PHPUnit is installed
        $this->ensurePHPUnitInstalled();

        // Step 4: Run Pint
        $this->runPint();

        // Step 5: Run PHPStan
        $this->runPHPStan();

        // Step 6: Run Unit Tests
        $this->runTests();

        // Step 7: Select Files to Stage
        $this->selectFilesToStage();

        // Step 8: Commit and Push Changes
        $this->commitAndPush();
    }

    protected function ensurePintInstalled()
    {
        if (! file_exists(base_path('vendor/bin/pint'))) {
            $installPint = confirm('Laravel Pint is not installed. Would you like to install it?', true);
            if ($installPint) {
                shell_exec('composer require laravel/pint --dev');
                $this->info('Laravel Pint installed successfully.');
            } else {
                $this->error('Laravel Pint is required. Aborting.');
                exit(1);
            }
        }
    }

    protected function ensurePHPStanInstalled()
    {
        if (! file_exists(base_path('vendor/bin/phpstan'))) {
            $installPHPStan = confirm('PHPStan is not installed. Would you like to install it?', true);
            if ($installPHPStan) {
                shell_exec('composer require phpstan/phpstan --dev');
                $this->info('PHPStan installed successfully.');
            } else {
                $this->error('PHPStan is required. Aborting.');
                exit(1);
            }
        }
    }

    protected function ensurePHPUnitInstalled()
    {
        if (! file_exists(base_path('vendor/bin/pest'))) {
            $installPHPUnit = confirm('PHP Pest is not installed. Would you like to install it?', true);
            if ($installPHPUnit) {
                $this->info('Removing PHP Unit...');
                shell_exec('composer remove phpunit/phpunit');
                $this->info('Adding PHP Pest with all dependencies...');
                shell_exec('composer require pestphp/pest --dev --with-all-dependencies');
                $this->info('PHP Pest installed successfully.');
            } else {
                $this->error('PHP Pest is required. Aborting.');
                exit(1);
            }
        }
    }

    protected function runPint()
    {
        if ($this->confirm('Would you like to run Laravel Pint?', true)) {
            spin(
                function () {
                    $pintOutput = shell_exec('./vendor/bin/pint');
                    $this->info($pintOutput);
                    // Check if there were errors
                    if (str_contains($pintOutput, 'FAIL') || str_contains($pintOutput, 'ERROR')) {
                        $this->error('Laravel Pint found errors. Please fix them before proceeding.');
                        exit(1); // Abort if there were errors
                    }
                },
                'Running Laravel Pint...'
            );
        } else {
            $this->info('Skipping Laravel Pint.');
        }
    }

    protected function runPHPStan()
    {
        if ($this->confirm('Would you like to run PHPStan?', true)) {
            spin(
                function () {
                    $phpStanOutput = shell_exec('./vendor/bin/phpstan analyse');
                    $this->info($phpStanOutput);
                    // Check if there were errors
                    if (str_contains($phpStanOutput, 'errors')) {
                        $this->error('PHPStan found errors. Please fix them before proceeding.');
                        exit(1); // Abort if there were errors
                    }
                },
                'Running PHPStan...'
            );
        } else {
            $this->info('Skipping PHPStan.');
        }
    }

    protected function runTests()
    {
        if ($this->confirm('Would you like to run Pest Tests? If you need more detailed tests, please run it manually with flags you need.', true)) {
            spin(
                function () {
                    $testOutput = shell_exec('./vendor/bin/pest');
                    $this->info($testOutput);
                    // Check if there were errors
                    if (str_contains($testOutput, 'Fail') || str_contains($testOutput, 'Error')) {
                        $this->error('Your PEST tests failed. Please fix them before proceeding.');
                        exit(1); // Abort if there were errors
                    }
                },
                'Running Pest Tests...'
            );
        } else {
            $this->info('Skipping Pest Tests.');
        }
    }

    protected function selectFilesToStage(): void
    {
        $changes = $this->getGitChanges();
        $files = multisearch(
            label: 'Select files to stage (space to select, enter to confirm)',
            options: fn () => array_merge(['Select All', 'Cancel'], $changes), // Include "Select All" and "Cancel"
            required: true
        );

        if (in_array('Cancel', $files)) {
            $this->info('Operation cancelled.');
            exit(0); // Exit without an error status
        }

        if (in_array('Select All', $files)) {
            $files = $changes; // If "Select All" is selected, stage all files
        }

        $this->info('Staging files: '.implode(', ', $files));
        shell_exec('git add '.implode(' ', $files));
    }

    protected function commitAndPush()
    {
        $commitMessage = text('Enter the commit message');
        if (! $commitMessage) {
            $this->error('Commit message cannot be empty.');
            exit(1); // Abort if commit message is empty
        }

        $this->info('Staging changes...');
        shell_exec('git add .');

        $this->info('Committing changes...');
        shell_exec("git commit -m \"$commitMessage\"");

        // Get the current branch name
        $process = new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
        $process->run();
        $currentBranch = trim($process->getOutput());

        // Prefill branch name and add options
        $branch = $this->ask('Pushing code to', $currentBranch);
        while (! $branch) {
            $this->error('Branch name cannot be empty.');
            $branch = $this->ask('Pushing code to', $currentBranch);
        }

        $this->info("Pushing code to branch: $branch");
        $process = new Process(['git', 'push', 'origin', $branch]);
        $process->run();

        if ($process->isSuccessful()) {
            $this->info('Code pushed successfully.');
        } else {
            $this->error('Failed to push code.');
            $this->error($process->getErrorOutput());
        }
    }

    protected function getGitChanges()
    {
        $output = shell_exec('git status -s');
        $lines = explode("\n", trim($output));
        $changes = [];

        foreach ($lines as $line) {
            if (! empty($line)) {
                $changes[] = trim(substr($line, 3)); // Extract the file path
            }
        }

        return $changes;
    }
}
