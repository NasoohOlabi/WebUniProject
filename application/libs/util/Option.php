<?php

abstract class Either
{
    abstract function combine($other);
}

namespace Either;

use Either;

class Err extends Either
{
    public $err;
    function __construct($err)
    {
        simpleLog($err);
        $this->err = $err;
    }
    function combine($other)
    {
        if ($other instanceof Either) {
            if ($other instanceof Err) {
                if (is_string($this->err) && is_string($this->err)) {
                    return new Err($this->err . $other->err);
                } else {
                    return new Err(array($this->left, $other->left));
                }
            } else {
                return $this;
            }
        }
    }
}
class Result extends Either
{
    public $result;
    function __construct($result = null)
    {
        $this->result = ($result == null) ? true : $result;
    }
    function combine($other)
    {
        if ($other instanceof Either) {
            if ($other instanceof Err) {
                return $other;
            } else {
                if (is_string($this->result) && is_string($this->result)) {
                    return new Result($this->result . $other->result);
                } else {
                    return new Result(array($this->result, $other->result));
                }
            }
        }
    }
}
