<?php

namespace App\Commands;

use App\Concerns\EnsureHasPloiConfiguration;
use App\Concerns\EnsureHasToken;
use App\Support\Configuration;
use App\Support\Ploi;
use Exception;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{

    use EnsureHasToken;
    use EnsureHasPloiConfiguration;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @param Ploi $ploi
     * @param Configuration $configuration
     * @return mixed
     */
    public function handle(Ploi $ploi, Configuration $configuration)
    {
        $this->ensureHasToken();
        if ($this->hasPloiConfiguration()) {
            $this->error('This site is already initialized!');
            exit(1);
        }

        $servers = $ploi->getServers();
        $selectedServer = $this->menu('Which server you want to use?', collect($servers)->map(function ($server) {
            return $server['name'] . ' - ' . $server['ip_address'];
        })->toArray())->open();
        if ($selectedServer === null) exit(1);
        $serverId = $servers[$selectedServer]['id'];

        $createNewSite = $this->confirm('Do want to create a new site on that server?', 1);

        if ($createNewSite) {
            [$siteId, $domain] = $this->createNewSite($ploi, $serverId);
            $this->info("Created a new site with id {$siteId}!");
        } else {
            $sites = $ploi->getSites($serverId);
            $selectedDomain = $this->menu('On which site you want to install the repository?', collect($sites)->map(function ($site) {
                return $site['domain'];
            })->toArray())->open();
            if ($selectedDomain === null) exit(1);

            $siteId = $sites[$selectedDomain]['id'];
            $domain = $sites[$selectedDomain]['domain'];
        }

        $configuration->initialize($serverId, $siteId, getcwd(), $domain);

        if(isset($selectedDomain)) {
            $this->info("Your project got linked to {$domain}!");
            exit(0);
        }

        $gitRepo = $this->getGitRepositoryUrl();
        if (Str::startsWith($gitRepo, 'fatal')) {
            $this->error('This site is no git repository!');
            exit(1);
        }

        if (!$this->installRepository($ploi, $serverId, $siteId, $gitRepo)) {
            $this->error('An error occurred!');
            exit(1);
        }

        $testDomain = $ploi->enableTestDomain($serverId, $siteId)['full_test_domain'];

        $this->info('Repository initialized! Go an do some great stuff 🚀');
        $this->warn("You can see your project under: {$testDomain}");
    }

    protected function installRepository(Ploi $ploi, $server, $site, $repo)
    {
        $provider = $this->ask('Which provider do you use?', 'github');
        if ($provider != 'custom') {
            $repo = str_replace('.git', '', $repo);
            if (Str::contains($repo, ':')) {
                $repo = explode(':', $repo)[1];
            } else {
                $parts = explode('/', $repo);
                $length = sizeof($parts);
                $repo = $parts[$length - 2] . '/' . $parts[$length - 1];
            }
        }
        $repo = $this->ask('Please confirm your repository url', $repo);
        $branch = $this->ask('Which branch should get installed?', 'main');

        return $ploi->installRepository(
            $server,
            $site,
            $provider,
            $branch,
            $repo
        );
    }

    protected function createNewSite(Ploi $ploi, $server): array
    {
        $rootDomain = $this->ask('What should the domain for your new site be?');
        $webDirectory = $this->ask('Which directory should be the web directory?', '/public');
        $systemUser = $this->ask('Which system user should run the site?', 'ploi');

        try {
            return [$ploi->createSite(
                $server,
                $rootDomain,
                $webDirectory,
                $systemUser
            ), $rootDomain];
        } catch (Exception $exception) {
            $this->error('An error occurred! ' . $exception->getMessage());
            $this->error('Please make sure to provide the url without http(s)://');
            exit(1);
        }
    }

    protected function getGitRepositoryUrl()
    {
        return exec('git remote get-url origin', $output);
    }

}
