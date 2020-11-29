<?php

namespace App\Commands;

use App\Concerns\EnsureHasPloiConfiguration;
use App\Concerns\EnsureHasToken;
use App\Support\Configuration;
use App\Support\Ploi;
use LaravelZero\Framework\Commands\Command;

class PullEnvCommand extends Command
{

    use EnsureHasToken;
    use EnsureHasPloiConfiguration;

    protected $signature = 'env:pull {--filename=}';
    protected $description = 'Pull the current environment';

    public function handle(Ploi $ploi, Configuration $configuration)
    {
        $this->ensureHasToken();
        $this->ensureHasPloiConfiguration();

        $filename = $this->option('filename') ?? '.env.ploi';

        $env = $ploi->getEnvironmentFile($configuration->get('server'), $configuration->get('site'));

        file_put_contents($filename, $env);

        $this->info("✅ Saved remote environment to {$filename}.");
    }

}
