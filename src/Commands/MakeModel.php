<?php

namespace Lunarstorm\LaravelDDD\Commands;

use Symfony\Component\Console\Input\InputArgument;

class MakeModel extends DomainGeneratorCommand
{
    protected $name = 'ddd:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a domain model';

    protected $type = 'Model';

    protected function getArguments()
    {
        return [
            ...parent::getArguments(),

            new InputArgument(
                'name',
                InputArgument::REQUIRED,
                'The name of the model',
            ),
        ];
    }

    protected function getStub()
    {
        return $this->resolveStubPath('model.php.stub');
    }

    protected function getRelativeDomainNamespace(): string
    {
        return config('ddd.namespaces.models', 'Models');
    }

    public function handle()
    {
        $baseModel = config('ddd.base_model');

        $parts = str($baseModel)->explode('\\');
        $baseModelName = $parts->last();
        $baseModelPath = $this->getPath($baseModel);

        if (! file_exists($baseModelPath)) {
            $this->warn("Base model {$baseModel} doesn't exist, generating...");

            $this->call(MakeBaseModel::class, [
                'domain' => 'Shared',
                'name' => $baseModelName,
            ]);
        }

        parent::handle();
    }
}
