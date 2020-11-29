<?php

namespace App\Commands;

use App\Concerns\EnsureHasPloiConfiguration;
use App\Concerns\EnsureHasToken;
use App\Support\Configuration;
use App\Support\Ploi;
use LaravelZero\Framework\Commands\Command;

class PullDeployScriptCommand extends Command
{

    use EnsureHasToken;
    use EnsureHasPloiConfiguration;

    protected $signature = 'deploy:pull {--filename=}';
    protected $description = 'Pulls the current deploy script';

    public function handle(Ploi $ploi, Configuration $configuration)
    {
        $this->ensureHasToken();
        $this->ensureHasPloiConfiguration();

        $filename = $this->option('filename') ?? 'deploy.sh';

        $env = $ploi->getDeployScript($configuration->get('server'), $configuration->get('site'));

        file_put_contents($filename, $env);

        $this->info("✅ Saved remote deploy script to {$filename}.");
    }

}
