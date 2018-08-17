<?php

namespace CorePHP\Requests;

use Psr\Http\Message\StreamInterface;

class RequestBody implements StreamInterface
{
    /**
     * Body content
     *
     * @var string
     */
    private $content;

    /**
     * Get instance of the Request Body
     *
     * @param mixed $body
     * @return RequestBody
     */
    public static function getInstance($body=null)
    {
        if (empty($body)) {
            return new RequestBody();
        }

        if (!is_string($body)) {
            $body = serialize($body);
        }

        return self::getInstance()
            ->write($body);
    }

    /**
     * Write the request body content
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     */
    public function write($string)
    {
        $this->content = $string;
        return count(unpack('C*', $string));
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     */
    public function getContents()
    {
        return $this->content;
    }

    /**
     * Return the original entry of the Request body
     *
     * @return mixed
     */
    public function getOriginalContents()
    {
        if (@unserialize($this->content) === false) {
            return $this->content;
        }

        return unserialize($this->content);
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize()
    {
        if (empty($this->content)) {
            return null;
        }

        return count(unpack('C*', $this->content));
    }

    /**
     * Return all data of the Request body
     *
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }

    public function close()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function detach()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function tell()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function eof()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function isSeekable()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function rewind()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function isWritable()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function isReadable()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function getMetadata($key = null)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }
}