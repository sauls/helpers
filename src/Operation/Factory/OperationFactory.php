<?php
/**
 * This file is part of the sauls/helpers package.
 *
 * @author    Saulius Vaičeliūnas <vaiceliunas@inbox.lt>
 * @link      http://saulius.vaiceliunas.lt
 * @copyright 2018
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sauls\Component\Helper\Operation\Factory;

use Sauls\Component\Helper\Operation\Operation;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class OperationFactory implements OperationFactoryInterface
{
    /**
     * @var ContainerBuilder
     */
    private static $container;
    private static $isContainerInitialised = false;

    /**
     * @throws \Exception
     */
    public static function create(string $operationClass): Operation
    {
        if (false === self::$isContainerInitialised) {
            self::initialiseContainer();
        }

        return self::$container->get($operationClass);
    }

    /**
     *
     * @throws \Exception
     */
    private static function initialiseContainer(): void
    {
        self::$container = new ContainerBuilder();

        $loader = new YamlFileLoader(self::$container, new FileLocator([__DIR__ . '/../../Resources/config']));
        $loader->load('operations.yml');

        self::$container->compile();

        self::$isContainerInitialised = true;
    }
}
