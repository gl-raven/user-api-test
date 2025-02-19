<?php

namespace Model;

interface IValidate
{
    public function validate();

    public function getErrors();
}