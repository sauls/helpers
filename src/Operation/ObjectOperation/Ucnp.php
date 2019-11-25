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

use Sauls\Component\Helper\Operation\ClassOperation\UcnpInterface as ClassOperationUcpnInterface;

class Ucnp implements UcnpInterface
{
    /**
     * @var ClassOperationUcpnInterface
     */
    private $classUcpn;

    public function __construct(ClassOperationUcpnInterface $classUcpn)
    {

        $this->classUcpn = $classUcpn;
    }

    public function execute(object $value): string
    {
        return $this->classUcpn->execute(\get_class($value));
    }
}
