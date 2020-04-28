<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RepositoryInterfaceToServiceMapper implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $definition) {
            $className = $definition->getClass();
            if (!is_string($className)) {
                continue;
            }

            if (strpos($className, 'Infrastructure') !== false) {
                $possibleAliases = [
                    str_replace('Infrastructure', 'Domain', $className),
                    str_replace('Infrastructure', 'Domain', $className) . 'Interface',
                ];

                foreach ($possibleAliases as $alias) {
                    if (interface_exists($alias)) {
                        $container->setAlias($alias, $className);
                    }
                }
            }
        }
    }
}