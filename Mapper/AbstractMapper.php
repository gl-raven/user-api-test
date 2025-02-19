<?php


namespace Mapper;


use PDO;

abstract class AbstractMapper
{
    public function __construct(protected PDO $db)
    {

    }
}
