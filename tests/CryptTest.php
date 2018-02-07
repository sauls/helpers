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

namespace Sauls\Component\Helper;

use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use PHPUnit\Framework\TestCase;

class CryptTest extends TestCase
{
    /**
     * @test
     */
    public function should_encode_and_decode_data()
    {

        $data = [
            'custom' => 'data',
            'collection' => [
                'a' => 'b',
                'c' => 'd',
            ],
            'float' => 5.669
        ];

        $hash = data_encrypt($data, 'test');

        $this->assertTrue(\is_string($hash));

        $this->assertSame($data, data_decrypt($hash, 'test'));

        $this->assertArrayHasKey('collection', $data);
    }

    /**
     * @test
     *
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function should_fail_to_decode_data_with_wrong_key()
    {
        $this->expectException(WrongKeyOrModifiedCiphertextException::class);

        $data = "test data";

        $hash = data_encrypt($data, 'testkey');

        data_decrypt($hash, 'mykey');
    }

    /**
     * @test
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws WrongKeyOrModifiedCiphertextException
     */
    public function should_fail_to_decode_data_with_modified_cypher_text()
    {
        $this->expectException(WrongKeyOrModifiedCiphertextException::class);
        $data = "test data";

        $hash = data_encrypt($data, 'testkey') . 'HgtQQQ';

        data_decrypt($hash, 'testkey');
    }
}
