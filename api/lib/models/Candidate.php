<?php

class Candidate extends DBA
{
    protected const   TABLE = 'candidate';
    private ?int $id;
    private ?int $token;
    private string $photo;
    private string $description;
    private ?string $slogan;
    private bool $confirmed;
    private Contact $contact;
    private Competition $competition;
    private User $createdby;

}