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

namespace Chrisguitarguy\FileIterator;

/**
 * Read delimited files (CSV's and TSV's).
 *
 * @since   0.1
 */
class SplitterIterator extends BaseIterator
{
    private $delim;
    private $enclosure;

    public function __construct($filename, $delim=',', $enclosure='"')
    {
        parent::__construct($filename);
        $this->delim = $delim;
        $this->enclosure = $enclosure;
    }

    /**
     * {@inheritdoc}
     */
    public function getLine()
    {
        $enc = $this->enclosure;

        $line = trim(fgets($this->getResource()), "\r\n");

        return array_map(function($elem) use ($enc) {
            return trim($elem, $enc);
        }, str_getcsv($line, $this->delim, $this->enclosure));
    }
}
