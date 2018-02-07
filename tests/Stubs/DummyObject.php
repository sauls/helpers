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

namespace Sauls\Component\Helper\Stubs;


class DummyObject
{
    /**
     * @var string
     */
    private $property1 = '';

    /**
     * @var string
     */
    private $secret = 'secret string';

    /**
     * @var string
     */
    public $property2 = '';

    /**
     * @var string
     */
    public $property3 = '';

    /**
     * @var string
     */
    public $text = '';

    public function getProperty1()
    {
        return $this->property1;
    }

    public function setProperty1($property1)
    {
        $this->property1 = $property1;
    }
}
