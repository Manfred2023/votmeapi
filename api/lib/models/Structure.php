<?php

class Structure extends DBA
{
    protected const   TABLE = 'structure';
    private ?int $id;
    private ?int $token;
    private string $name;
    private string $mobile;
    private City $city;
    private ?Contact $manager;

}