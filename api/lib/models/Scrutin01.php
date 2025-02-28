<?php

class Scrutin01 extends DBA
{
    protected const   TABLE = 'scrutin01';
    private ?int $id;
    private ?int $token;
    private ?Competition $competition;
    private ?Candidate $candidate;
    private ?int $amount;
    private ?Paiement $paiement;
    private string $reference;
    private DateTime $created;
}