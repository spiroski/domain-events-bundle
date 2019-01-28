<?php namespace Spiroski\Domain\Events\Bundle\DependencyInjection;

use Spiroski\Domain\Events\Bundle\DependencyInjection\Compiler\ListenerPass;
use Spiroski\Domain\Events\Bundle\DependencyInjection\Compiler\SubscriberPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SpiroskiDomainEventsExtension extends Extension implements CompilerPassInterface
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }


    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ListenerPass());
        $container->addCompilerPass(new SubscriberPass());
    }
}