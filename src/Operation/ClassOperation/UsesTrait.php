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

namespace Sauls\Component\Helper\Operation\ClassOperation;

class UsesTrait implements UsesTraitInterface
{
    /**
     * @var TraitsInterface
     */
    private $traitsOperation;

    public function __construct(TraitsInterface $traitsOperation)
    {
        $this->traitsOperation = $traitsOperation;
    }

    public function execute(string $class, string $traitClass): bool
    {
        return \in_array($traitClass, $this->traitsOperation->execute($class));
    }
}
