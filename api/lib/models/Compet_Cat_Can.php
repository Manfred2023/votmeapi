<?php

class Compet_Cat_Can extends DBA
{
    protected const   TABLE = 'compet_cat_can';
    private ?int $id;
    private ?int $token;
    private ?Competition_Category $competitioncategory;
    private ?Candidate $candidate;

}