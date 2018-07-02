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

namespace Sauls\Component\Helper\Operation\ObjectOperation;

use Sauls\Component\Helper\Operation\ClassOperation\FqcnInterface as ClassFqcnInterface;

class Fqcn implements FqcnInterface
{
    /**
     * @var ClassFqcnInterface
     */
    private $classFcqn;

    public function __construct(ClassFqcnInterface $classFcqn)
    {
        $this->classFcqn = $classFcqn;
    }

    public function execute(object $value): string
    {
        return $this->classFcqn->execute(\get_class($value));
    }
}