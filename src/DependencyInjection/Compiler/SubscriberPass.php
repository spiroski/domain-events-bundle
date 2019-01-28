<?php namespace Spiroski\Domain\Events\Bundle\DependencyInjection\Compiler;

use Spiroski\Domain\Events\BasicDispatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SubscriberPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(BasicDispatcher::class)) {
            return;
        }

        $definition = $container->findDefinition(BasicDispatcher::class);

        $taggedServices = $container->findTaggedServiceIds('spiroski_domain_events.subscriber');

        foreach ($taggedServices as $subscriberId => $tags) {
            $definition->addMethodCall('registerSubscriber', [new Reference($subscriberId)]);
        }
    }
}