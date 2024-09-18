<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;
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

        $fixedFiles = $this->runPint();

        $this->runTests();

        $this->stageFixedFiles($fixedFiles);

        $this->commitChanges();
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

    protected function runPint(): array
    {
        $fixedFiles = [];

        if (confirm('Would you like to run Laravel Pint?', true)) {
            spin(
                function () use (&$fixedFiles) {
                    $pintOutput = shell_exec('./vendor/bin/pint --dirty');
                    $this->info($pintOutput);

                    // Collect the list of fixed files from the output
                    preg_match_all('/^\s+✓ (\S+)/m', $pintOutput, $matches);
                    if (isset($matches[1])) {
                        $fixedFiles = $matches[1];
                    }

                    // Check if there were errors
                    if (str_contains($pintOutput, 'FAIL') || str_contains($pintOutput, 'ERROR')) {
                        $this->error('Laravel Pint found errors. Please fix them before proceeding.');
                        exit(1); // Abort if there were errors
                    }
                },
                'Running Laravel Pint...'
            );

            // Ensure we stage any fixed files right after Pint runs
            foreach ($fixedFiles as $file) {
                shell_exec("git add $file");
                $this->info("Staged fixed file: $file");
            }
        } else {
            $this->info('Skipping Laravel Pint.');
        }

        return $fixedFiles;
    }

    protected function runTests(): void
    {
        if (confirm('Would you like to run Pest Tests? If you need more detailed tests, please run it manually with flags you need.', true)) {
            spin(
                function () {
                    $testOutput = shell_exec('./vendor/bin/pest');
                    $this->info($testOutput);

                    // Check if there were errors
                    if (str_contains($testOutput, 'Fail') || str_contains($testOutput, 'Error')) {
                        $this->error('Your Pest tests failed. Please fix them before proceeding.');
                        exit(1); // Abort if there were errors
                    }
                },
                'Running Pest Tests...'
            );
        } else {
            $this->info('Skipping Pest Tests.');
        }
    }

    protected function stageFixedFiles(array $fixedFiles): void
    {
        // Stage the fixed files
        foreach ($fixedFiles as $file) {
            shell_exec("git add $file");
            $this->info("Staged fixed file: $file");
        }

        // Check if there are any staged files
        $stagedFiles = shell_exec('git diff --cached --name-only');
        if (empty(trim($stagedFiles))) {
            $this->info('No changes staged for commit.');
        }
    }

    protected function commitChanges(): void
    {
        // Ensure there are staged files
        $stagedFiles = shell_exec('git diff --cached --name-only');
        if (empty(trim($stagedFiles))) {
            $this->info('No changes staged for commit.');

            return;
        }

        $commitMessage = text('Enter the commit message');
        if (! $commitMessage) {
            $this->error('Commit message cannot be empty.');
            exit(1); // Abort if commit message is empty
        }

        // Commit the staged files
        shell_exec("git commit -m \"$commitMessage\"");
        $this->info('Changes committed.');

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

        // Push the changes
        $process = new Process(['git', 'push', 'origin', $branch]);
        $process->run();
        $this->info($process->getOutput());
        if ($process->isSuccessful()) {
            $this->info('Code pushed successfully.');
        } else {
            $this->error('Failed to push code.');
        }

        // Get the commit hash
        $commitHash = shell_exec('git log -1 --format="%H"');
        $commitHash = trim($commitHash);

        // Get the Git username who pushed
        $gitUserName = shell_exec('git config user.name');
        $gitUserName = trim($gitUserName);

        $this->info("Commit hash: $commitHash");
        $this->info("Pushed by: $gitUserName");
    }
}
