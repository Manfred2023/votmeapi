<?php

class Candidate extends DBA
{
    protected const   TABLE = 'candidate';
    private ?int $id;
    private ?int $token;
    private string $photo;
    private string $description;
    private ?string $slogan;
    private Contact $contact;
    private Competition $competition;

}