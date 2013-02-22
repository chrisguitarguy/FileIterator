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

abstract class BaseIterator implements \Iterator
{
    /**
     * Container for our open file.
     *
     * @since   0.1
     * @access  private
     * @var     resource
     */
    private $resource;

    /**
     * Container for the filename.
     *
     * @since   0.1
     * @access  private
     * @var     string
     */
    private $filename;

    /**
     * Container for the current line.
     *
     * @since   0.1
     * @access  private
     * @var     mixed
     */
    private $line = null;

    /**
     * Whether or not the iterator is still valid.
     *
     * @since   0.1
     * @access  private
     * @var     boolean
     */
    private $valid = true;

    /**
     * The "key" (line count) of our iterator.
     *
     * @since   0.1
     * @access  private
     * @var     integer
     */
    private $index = -1;

    /**
     * Constructor. Set up the the filename and resource.
     *
     * @since   0.1
     * @access  public
     * @param   string $filename
     * @return  void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->open();
    }

    /**
     * Destructor. Close the resource if it's open.
     *
     * @since   0.1
     * @access  public
     * @return  void
     */
    public function __destruct()
    {
        if (is_resource($this->resource)) {
            fclose($this->resource);
        }
    }

    /**
     * From Iterator
     *
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->line;
    }

    /**
     * From Iterator
     *
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * From Iterator
     *
     * {@inheritdoc}
     */
    public function next()
    {
        if (!feof($this->getResource())) {
            $this->valid = true;
            $this->line = $this->getLine();
            $this->index++;
        } else {
            $this->valid = false;
        }
    }

    /**
     * From Iterator
     *
     * {@inheritdoc}
     */
    public function rewind()
    {
        rewind($this->getResource());
        $this->index = -1;
        $this->next();
    }

    /**
     * From Iterator
     *
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     * Read a line from the resource. Subclasses should deal with this.
     *
     * @since   0.1
     * @access  protected
     * @return  mixed
     */
    abstract protected function getLine();

    /**
     * Get the resource.
     *
     * @since   0.1
     * @access  protected
     * @return  resource
     */
    protected function getResource()
    {
        if (!is_resource($this->resource)) {
            throw new Exception\NoResourceException("Resource is not open");
        }

        return $this->resource;
    }

    /**
     * Open the resource or throw an exception if it fails.
     *
     * @since   0.1
     * @access  private
     * @throws  Chrisguitarguy\FileIterator\Exception
     */
    private function open()
    {
        $hndl = @fopen($this->filename, 'rb');

        if (!$hndl) {
            $err = error_get_last();
            throw new Exception\ResourceOpenException("Error opening {$this->filename} - " . $err['message']);
        }

        $this->resource = $hndl;
    }
}
