<?php

namespace App\Commands;

use App\Concerns\EnsureHasToken;
use App\Support\Ploi;
use App\Support\TokenNodeVisitor;
use Exception;
use LaravelZero\Framework\Commands\Command;
use PhpParser\Lexer\Emulative;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\Parser\Php7;
use PhpParser\PrettyPrinter\Standard;

class TokenCommand extends Command
{

    use EnsureHasToken;

    protected $signature = 'token {token} {--force}';
    protected $description = 'Store your ploi api token.';

    public function handle()
    {
        if ($this->hasToken() && !$this->option('force')) {
            $this->warn('You are have set a token! Use "ploi token --force" to override your token.');
            return false;
        }

        $token = $this->argument('token');

        $this->line('Checking token...');

        if ($this->checkToken($token)) {
            $this->info('✅ Token valid.');
            $this->saveToken($token);
            $this->info('✅ Token saved!');
        } else {
            $this->error('❎ Cannot login with given token!');
        }
    }

    protected function checkToken(string $token)
    {
        $ploi = new Ploi($token);

        try {
            $ploi->getServers();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function modifyConfigurationFile(string $configFile, string $token)
    {
        $lexer = new Emulative([
            'usedAttributes' => [
                'comments',
                'startLine',
                'endLine',
                'startTokenPos',
                'endTokenPos',
            ],
        ]);
        $parser = new Php7($lexer);

        $oldStmts = $parser->parse(file_get_contents($configFile));
        $oldTokens = $lexer->getTokens();

        $nodeTraverser = new NodeTraverser;
        $nodeTraverser->addVisitor(new CloningVisitor());
        $newStmts = $nodeTraverser->traverse($oldStmts);

        $nodeTraverser = new NodeTraverser;
        $nodeTraverser->addVisitor(new TokenNodeVisitor($token));

        $newStmts = $nodeTraverser->traverse($newStmts);

        $prettyPrinter = new Standard();

        return $prettyPrinter->printFormatPreserving($newStmts, $oldStmts, $oldTokens);
    }

    protected function saveToken($token)
    {
        $configFile = implode(DIRECTORY_SEPARATOR, [
            $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'],
            '.ploi',
            'config.php',
        ]);

        if (!file_exists($configFile)) {
            @mkdir(dirname($configFile), 0777, true);
            $updatedConfigFile = $this->modifyConfigurationFile(base_path('config/ploi.php'), $token);
        } else {
            $updatedConfigFile = $this->modifyConfigurationFile($configFile, $token);
        }

        file_put_contents($configFile, $updatedConfigFile);

        return;
    }

}
