<?php

class Status
{
    protected const   TABLE = 'status';
    private ?int $id;
    private ?int $token;
    private ?int $position;
    private string $color;
    private string $label;
    private string $reference;
    private string $description;
    private Family $family;
}