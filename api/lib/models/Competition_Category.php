<?php

class Competition_Category extends DBA
{
    protected const   TABLE = 'competition_category';
    private ?int $id;
    private ?int $token;
    private ?Competition $competition;
    private ?Category $category;
}