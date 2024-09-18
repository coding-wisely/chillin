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
        $this->ensureToolInstalled('vendor/bin/pint', 'Laravel Pint is not installed. Would you like to install it?', 'composer require laravel/pint --dev', 'Laravel Pint installed successfully.');
    }

    protected function ensurePestIsInstalled(): void
    {
        $this->ensureToolInstalled('vendor/bin/pest', 'PHP Pest is not installed. Would you like to install it?', 'composer require pestphp/pest --dev --with-all-dependencies', 'PHP Pest installed successfully.', 'composer remove phpunit/phpunit', 'Removing PHP Unit...');
    }

    private function ensureToolInstalled(string $path, string $confirmMessage, string $installCommand, string $successMessage, string $preCommand = '', string $preMessage = ''): void
    {
        if (! file_exists(base_path($path))) {
            if (confirm($confirmMessage, true)) {
                if ($preCommand) {
                    $this->info($preMessage);
                    shell_exec($preCommand);
                }
                shell_exec($installCommand);
                $this->info($successMessage);
            } else {
                $this->error("$confirmMessage is required. Aborting.");
                exit(1);
            }
        }
    }

    protected function runPint(): array
    {
        return $this->runTool('Laravel Pint', './vendor/bin/pint --dirty', '/^\s+✓ (\S+)/m');
    }

    protected function runTests(): void
    {
        $this->runTool('Pest Tests', './vendor/bin/pest', '', 'Fail', 'Error');
    }

    private function runTool(string $toolName, string $command, string $regexPattern = '', string $errorKeyword1 = 'FAIL', string $errorKeyword2 = 'ERROR'): array
    {
        $outputFiles = [];
        if (confirm("Would you like to run $toolName?", true)) {
            spin(
                function () use ($command, &$outputFiles, $regexPattern, $errorKeyword1, $errorKeyword2) {
                    $output = shell_exec($command);
                    $this->info($output);

                    if ($regexPattern) {
                        preg_match_all($regexPattern, $output, $matches);
                        $outputFiles = $matches[1] ?? [];
                    }

                    if (str_contains($output, $errorKeyword1) || str_contains($output, $errorKeyword2)) {
                        $this->error("$toolName found errors. Please fix them before proceeding.");
                        exit(1);
                    }
                },
                "Running $toolName..."
            );

            if ($regexPattern) {
                foreach ($outputFiles as $file) {
                    shell_exec("git add $file");
                    $this->info("Staged fixed file: $file");
                }
            }
        } else {
            $this->info("Skipping $toolName.");
        }

        return $outputFiles;
    }

    protected function stageFixedFiles(array $fixedFiles): void
    {
        foreach ($fixedFiles as $file) {
            shell_exec("git add $file");
            $this->info("Staged fixed file: $file");
        }
        $stagedFiles = shell_exec('git diff --cached --name-only');
        if (empty(trim($stagedFiles))) {
            $this->info('No changes staged for commit.');
        }
    }

    protected function commitChanges(): void
    {
        $stagedFiles = shell_exec('git diff --cached --name-only');
        if (empty(trim($stagedFiles))) {
            $this->info('No changes staged for commit.');

            return;
        }
        $commitMessage = text('Enter the commit message');
        if (! $commitMessage) {
            $this->error('Commit message cannot be empty.');
            exit(1);
        }
        shell_exec("git commit -m \"$commitMessage\"");
        $this->info('Changes committed.');

        $currentBranch = trim((new Process(['git', 'rev-parse', '--abbrev-ref', 'HEAD']))->run()->getOutput());
        $branch = $this->ask('Pushing code to', $currentBranch);

        while (! $branch) {
            $this->error('Branch name cannot be empty.');
            $branch = $this->ask('Pushing code to', $currentBranch);
        }

        $process = new Process(['git', 'push', 'origin', $branch]);
        $process->run();

        if ($process->isSuccessful()) {
            $this->info('Code pushed successfully.');
        } else {
            $this->error('Failed to push code.');
        }

        $commitHash = trim(shell_exec('git log -1 --format="%H"'));
        $gitUserName = trim(shell_exec('git config user.name'));
        $this->info("Commit hash: $commitHash");
        $this->info("Pushed by: $gitUserName");
    }
}
