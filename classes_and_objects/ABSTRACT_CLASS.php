<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 09.10.18
 * Time: 9:20
 */

////////////////////////////////////////////////////////////////////////////////////////////////////
/***************************************** ABSTRACT CLASS *****************************************/
////////////////////////////////////////////////////////////////////////////////////////////////////
abstract class ClassAbstract
{
    /**
     * Такой класс может содержать абстрактные методы. По сути, это лишь объявление методов,
     * которые должны быть реализованы в дочерних классах.
     */
    abstract public function getValue();

    /**
     * Но он также может содержать и обычные методы, которые будут содержать вполне себе реальную бизнес-логику. Пример:
     */
    public function printValue()
    {
        echo 'Значение: ' . $this->getValue();
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////
/*********************************** EXTEND ABSTRACT CLASS ****************************************/
////////////////////////////////////////////////////////////////////////////////////////////////////
class ClassA extends ClassAbstract
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

$objectA = new ClassA('SOME VALUE');
//$objectA->printValue(); // Берется из abstract class AbstractClass
