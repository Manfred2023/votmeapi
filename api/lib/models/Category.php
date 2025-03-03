<?php

class Category extends DBA
{
    protected const   TABLE = 'category';
    private ?int $id;
    private ?int $token;
    private string $label;
    private ?string $description;
}