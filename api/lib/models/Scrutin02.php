<?php

class Scrutin02 extends DBA
{
    protected const   TABLE = 'scrutin02';
    private ?int $id;
    private ?int $token;
    private string $mobile;
    private ?string $email;
    private ?DateTime $created;
    private Compet_Cat_Can $compet_cat_can;
}