<?php

/**
 * This file is part of the RequestStream package
 *
 * (c) Vitaliy Zhuk <zhuk2205@gmail.com>
 *
 * For the full copyring and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace RequestStream\Stream\Socket;

use RequestStream\Stream\Exception\SocketErrorException;

/**
 * Client socket connection
 */
class SocketClient extends Socket
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var integer
     */
    protected $flag = STREAM_CLIENT_CONNECT;

    /**
     * @{inerhitDoc}
     */
    public function setFlag($flag)
    {
        if (!in_array($flag, array(STREAM_CLIENT_CONNECT, STREAM_CLIENT_ASYNC_CONNECT, STREAM_CLIENT_PERSISTENT))) {
            throw new \InvalidArgumentException('Undefined flags in own system. Please check flags.');
        }

        $this->flag = $flag;

        return $this;
    }

    /**
     * @{inerhitDoc}
     */
    public function getFlag()
    {
        return $this->flag;
    }

        /**
     * @{inerhitDoc}
     */
    public function create()
    {
        if ($this->is(FALSE)) {
            return $this->resource;
        }

        if ($this->context) {
            $resource = @stream_socket_client($this->getRemoteSocket(), $errorCode, $errorStr, ini_get('default_socket_timeout'), $this->flag, $this->getContext(TRUE));
        }
        else {
            $resource = @stream_socket_client($this->getRemoteSocket(), $errorCode, $errorStr, ini_get('default_socket_timeout'), $this->flag);
        }

        if (!$resource) {
            if (!$errorCode && !$errorStr) {
                throw new SocketErrorException('Socket not create. Technical error in system.', 0);
            }
            else {
                throw new SocketErrorException($errorCode . ': ' . $errorStr, $errorCode);
            }
        }

        return $this->resource = $resource;
    }
}