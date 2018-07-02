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

use Sauls\Component\Helper\Operation\ClassOperation\UcnpInterface as ClassUfqcnInterface;

class Ucnp implements UcnpInterface
{
    /**
     * @var ClassFqcnInterface
     */
    private $classFcqn;

    public function __construct(ClassUfqcnInterface $classFcqn)
    {
        $this->classFcqn = $classFcqn;
    }

    public function execute(object $value): string
    {
        return $this->classFcqn->execute(\get_class($value));
    }
}
