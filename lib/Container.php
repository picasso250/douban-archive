<?php
namespace Lib;

use Exception;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private static $lazy = [];
    private static $pool = [];
    public static function getInstance() {
        return new self();
    }
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (isset(self::$pool[$id])) return self::$pool[$id];
        if (isset(self::$lazy[$id])) {
            $func = self::$lazy[$id];
            $res = $func();
            if ($res === null) throw new InitFailException("$id init fail");
            return self::$pool[$id] = $res;
        }
        throw new NotFoundExceptionInterface("$id not found");
    }
    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        if (isset(self::$pool[$id])) return true;
        if (isset(self::$lazy[$id])) {
            return true;
        }
        return false;
    }

    public function set($id, $v)
    {
        if (is_callable($v)) self::setLazy($id, $v);
        else self::setImmediate($id, $v);
    }
    public function setLazy($id, $v) {
        self::$lazy[$id] = $v;
    }
    public function setImmediate($id, $v) {
        self::$pool[$id] = $v;
    }
}

class InitFailException extends Exception implements ContainerExceptionInterface
{
}

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}
