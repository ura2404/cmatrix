<?php
class A {
    static $SSS;
    public $T;

    function pddd(){
	return static::$DDD;
    }
}

class B extends A {
    static $DDD;
    function __construct(){
	self::$SSS = 'class B // SSS';
	self::$DDD = 'class B // DDD';
	$this->T = 'class B // T';
    }
}

class C extends A {
    static $DDD;
    function __construct(){
	self::$SSS = 'class C // SSS';
	self::$DDD = 'class C // DDD';
	$this->T = 'class C // T';
    }
}


print("Class B----------------------\n");
$ObB = new B();
var_dump($ObB::$SSS);
var_dump($ObB::$DDD);
var_dump($ObB->pddd());
var_dump($ObB->T);

print("Class C----------------------\n");
$ObC = new C();
var_dump($ObC::$SSS);
var_dump($ObC::$DDD);
var_dump($ObC->pddd());
var_dump($ObC->T);

print("Class B----------------------\n");
var_dump($ObB::$SSS);
var_dump($ObB::$DDD);
var_dump($ObB->pddd());
var_dump($ObB->T);
?>