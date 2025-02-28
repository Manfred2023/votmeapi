<?php

class Paiement
{
    protected const   TABLE = 'paiement';
    private ?int $id;
    private ?int $token;
    private string $label;
    private ?string $bigram;
    private ?string $color;
    private ?Country $country;
}