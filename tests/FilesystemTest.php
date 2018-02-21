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

use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    /**
     * SetUp
     */
    protected function setUp()
    {
        $directory = $this->getTestDirectory();

        if (!file_exists($directory)) {
            mkdir($directory);

            mkdir($directory.'/test1');
            mkdir($directory.'/test2');
            touch($directory.'/test2/testfile1');
            touch($directory.'/test2/testfile2');
            mkdir($directory.'/test3');
            touch($directory.'/test3/testfile1');
        }
    }

    /**
     * TearDown
     */
    protected function tearDown()
    {
        $directory = $this->getTestDirectory();

        if (file_exists($directory)) {
            rrmdir($directory);
        }
    }

    protected function getTestDirectory(): string
    {
        return __DIR__ . '/tmp';
    }

    /**
     * @test
     */
    public function should_remove_directories(): void
    {
        $directory = $this->getTestDirectory();
        
        $this->assertTrue(rrmdir($directory.'/test2'));
        $this->assertFileExists($directory.'/test1');
        $this->assertFileNotExists($directory.'/test2/testfile1');
        $this->assertFileNotExists($directory.'/test2/testfile2');
        $this->assertFileNotExists($directory.'/test2');
        $this->assertFileExists($directory.'/test3/testfile1');
        $this->assertFalse(rrmdir($directory.'/test3/testfile1'));
        $this->assertTrue(rrmdir($directory));
        $this->assertFileNotExists($directory);
    }
}
