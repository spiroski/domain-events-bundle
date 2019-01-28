<?php namespace Spiroski\Domain\Events\Bundle\DependencyInjection\Compiler;

use Spiroski\Domain\Events\BasicDispatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ListenerPass implements CompilerPassInterface
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

        $taggedServices = $container->findTaggedServiceIds('spiroski_domain_events.listener');

        foreach ($taggedServices as $listenerId => $tags) {
            $this->registerListener($container, $definition, $listenerId);
        }
    }

    protected function registerListener(ContainerBuilder $container, Definition $definition, $listenerId)
    {
        $listenerClass = $container->findDefinition($listenerId)->getClass();

        $classReflection = new \ReflectionClass($listenerClass);

        $eventClass = $classReflection->getMethod('handle')->getParameters()[0]->getType()->getName();

        $definition->addMethodCall('registerListener', [$eventClass, new Reference($listenerId)]);
    }
}