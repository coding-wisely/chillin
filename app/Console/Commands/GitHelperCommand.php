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

    public function handle(): void
    {
        $this->ensurePintInstalled();

        $this->ensurePestIsInstalled();

        $this->runPint();

        $this->runTests();

        $this->selectFilesToStage();

        $this->commitAndPush();
    }

    protected function ensurePintInstalled(): void
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

    protected function ensurePestIsInstalled(): void
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

    protected function runPint(): void
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

    protected function runTests(): void
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

    protected function commitAndPush(): void
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

            // Get the list of committed files
            $committedFiles = $this->getCommittedFiles();

            if (! empty($committedFiles)) {
                $this->info('Files pushed:');
                foreach ($committedFiles as $file) {
                    $this->info(" - $file");
                }
            } else {
                $this->info('No files were committed.');
            }

            // Get the commit hash
            $commitHash = trim(shell_exec('git log -1 --format="%H"'));
            $this->info("Commit hash: $commitHash");

            // Get the Git username who pushed
            $gitUserName = trim(shell_exec('git config user.name'));
            $this->info("Pushed by: $gitUserName");
        } else {
            $this->error('Failed to push code.');
            $this->error($process->getErrorOutput());
        }
    }

    protected function getCommittedFiles(): array
    {
        // Get the last commit hash
        $lastCommitHash = trim(shell_exec('git log -1 --format="%H"'));
        // Get the files in the last commit
        $output = shell_exec("git diff-tree --no-commit-id --name-only -r $lastCommitHash");

        return array_filter(explode("\n", trim($output)));
    }

    protected function getGitChanges(): array
    {
        $output = shell_exec('git status -s');
        $lines = explode("\n", trim($output));
        $changes = [];

        foreach ($lines as $line) {
            if (! empty($line)) {
                $changes[] = trim(substr($line, 2)); // Extract the file path
            }
        }

        return $changes;
    }
}
