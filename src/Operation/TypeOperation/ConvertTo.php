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

namespace Sauls\Component\Helper\Operation\TypeOperation;

use function Sauls\Component\Helper\array_merge;
use Sauls\Component\Helper\Exception\ConverterNotFoundException;
use Sauls\Component\Helper\Operation\TypeOperation\Collection\ConverterCollectionInterface;

class ConvertTo implements ConvertToInterface
{
    /**
     * @var ConverterCollectionInterface
     */
    private $ensurerCollection;

    private static $additionalConverters = [];

    public function __construct(ConverterCollectionInterface $ensurerCollection)
    {
        $this->ensurerCollection = $ensurerCollection;
    }

    public static function addConverters($converters): void
    {
        self::$additionalConverters = array_merge(self::$additionalConverters, $converters);
    }

    /**
     * @throws \Sauls\Component\Helper\Exception\ConverterNotFoundException
     */
    public function execute($value, string $type)
    {
        $this->ensurerCollection->add(self::$additionalConverters);

        foreach ($this->ensurerCollection->get($type) as $ensurer) {
            if ($ensurer->supports($value)) {
                return $ensurer->convert($value);
            }
        }

        throw new ConverterNotFoundException(
            sprintf('Given value of `%s` does not have any converters to type `%s`.', \gettype($value), $type)
        );
    }
}
