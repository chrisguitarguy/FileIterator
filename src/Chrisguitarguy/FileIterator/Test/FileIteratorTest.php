<?php
/**
 * Chrisguitaruy\FileIterator
 *
 * Copyright (c) 2013 Christopher Davis <http://christopherdavis.me>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 * @package     FileIterator
 * @since       0.1
 * @author      Christopher Davis <http://christopherdavis.me>
 * @copyright   2013 Christopher Davis
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\FileIterator\Test;

use org\bovigo\vfs;
use Chrisguitarguy\FileIterator\FileIterator;

class FileIteratorTest extends \PHPUnit_Framework_TestCase
{
    private $root;

    public function setUp()
    {
        $this->root = vfs\vfsStream::setUp('Test');
    }

    /**
     * @expectedException Chrisguitarguy\FileIterator\Exception\ResourceOpenException
     */
    public function testNonExistentFileThrowsError()
    {
        $this->assertFalse(vfs\vfsStreamWrapper::getRoot()->hasChild('test.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test.txt'));
    }

    public function testLinesActuallyWork()
    {
        $lines = array(
            "A Line",
            "Another Line",
            "Not Another Line",
        );

        $this->addFile('test2.txt', $lines);

        $this->assertTrue($this->root->hasChild('test2.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test2.txt'));

        foreach ($iter as $index => $line) {
            $this->assertEquals($line, $lines[$index]);
        }
    }

    public function testLinesWorkTwice()
    {
        $lines = array(
            "A Line",
            "Another Line",
            "Not Another Line",
        );

        $this->addFile('test3.txt', $lines);

        $this->assertTrue($this->root->hasChild('test3.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test3.txt'));

        foreach ($iter as $index => $line) {
        }
        
        foreach ($iter as $index => $line) {
            $this->assertEquals($line, $lines[$index]);
        }
    }

    public function testCurrentIsNull()
    {
        $lines = array(
            "A Line",
            "Another Line",
            "Not Another Line",
        );

        $this->addFile('test3.txt', $lines);

        $this->assertTrue($this->root->hasChild('test3.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test3.txt'));

        $this->assertNull($iter->current());

        $iter->next();

        $this->assertNotNull($iter->current());
    }

    public function testLinesAreStrings()
    {
        $lines = array(
            "A Line",
            "Another Line",
            "Not Another Line",
        );

        $this->addFile('test4.txt', $lines);

        $this->assertTrue($this->root->hasChild('test4.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test4.txt'));

        foreach ($iter as $line) {
            $this->assertTrue(is_string($line));
        }
    }

    public function testCarriageReturnEndingsWork()
    {
        $lines = array(
            "A Line",
            "Another Line",
            "Not Another Line",
        );

        $this->addFile('test4.txt', $lines, "\r");

        $this->assertTrue($this->root->hasChild('test4.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test4.txt'));

        foreach ($iter as $index => $line) {
            $this->assertEquals($line, $lines[$index]);
        }

        $this->assertEquals($index, 2);
    }

    public function testCrlfEndingsWork()
    {
        $lines = array(
            "A Line",
            "Another Line",
            "Not Another Line",
        );

        $this->addFile('test4.txt', $lines, "\r\n");

        $this->assertTrue($this->root->hasChild('test4.txt'));

        $iter = new FileIterator(vfs\vfsStream::url('test4.txt'));

        foreach ($iter as $index => $line) {
            var_dump($index, $line);
            $this->assertEquals($line, $lines[$index]);
        }

        $this->assertEquals($index, 2);
    }

    private function addFile($fn, array $lines, $le="\n")
    {
        $file = new vfs\vfsStreamFile($fn);
        $file->at($this->root);

        $hndl = fopen(vfs\vfsStream::url($fn), 'w+b');

        fwrite($hndl, implode("\n", $lines));

        fclose($hndl);
    }
}
