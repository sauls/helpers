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

namespace Sauls\Component\Helper\Operation\ArrayOperation;

use Sauls\Component\Helper\Operation\ObjectOperation;

class GetValue implements GetValueInterface
{
    /**
     * @var ObjectOperation\GetPropertyValueInterface
     */
    private $objectGetPropertyValueOperation;

    public function __construct(
        ObjectOperation\GetPropertyValueInterface $objectGetPropertyValueOperation,
        KeyExistsInterface $arrayKeyExistsOperation
    ) {
        $this->objectGetPropertyValueOperation = $objectGetPropertyValueOperation;
    }

    /**
     * @return mixed
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    public function execute($array, $key, $default = null)
    {
        if (\is_callable($key)) {
            return $key($array, $default);
        }

        return $this->getValueUsingArrayKey($array, $key, $default);
    }

    /**
     * @return mixed
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    private function getValueUsingArrayKey($array, $key, $default)
    {
        if (\is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = $this->execute($array, $keyPart);
            }
            $key = $lastKey;
        }

        if ($this->keyExistsAndValueIsArray($key, $array)) {
            return $array[$key];
        }

        return $this->getValueUsingDotNotatedArrayKey($array, $key, $default);
    }

    private function keyExistsAndValueIsArray($key, $array): bool
    {
        return \is_array($array) && (isset($array[$key]) || \array_key_exists($key, $array));
    }

    /**
     * @return mixed
     * @throws \Sauls\Component\Helper\Exception\PropertyNotAccessibleException
     */
    private function getValueUsingDotNotatedArrayKey($array, $key, $default)
    {
        if ((bool)($pos = strrpos($key, '.')) !== false) {
            $array = $this->execute($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (\is_object($array)) {
            return $this->objectGetPropertyValueOperation->execute($array, $key) ?? $default;
        }

        if (\is_array($array)) {
            return $this->getValueOrFallbackToDefault($array, $key, $default);
        }

        return $default;
    }

    /**
     * @return mixed
     */
    private function getValueOrFallbackToDefault($array, $key, $default)
    {
        return (isset($array[$key]) || \array_key_exists($key, $array)) ? $array[$key] : $default;
    }
}
