<?php

class Scrutin01 extends DBA
{
    protected const   TABLE = 'scrutin01';
    private ?int $id;
    private ?int $token;
    private ?int $amount;
    private ?Paiement $paiement;
    private string $reference;
    private DateTime $created;
    private Compet_Cat_Can $compet_cat_can;
}