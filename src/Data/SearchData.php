<?php

namespace App\Data;

use App\Entity\Category;

class SearchData
{
    /**
     * Système permettant de rentrer un mot clé. C'est une chaîne de caractères
     * @var string
     */
    public $q ='';


    /**
     * Tableau des catégories sélectionnées
     * @var category[]
     */
    public $categories = [];


    /**
     * @var null|integer
     */
    public $max;


    /**
     * @var null|integer
     */
    public $min;


    /**
     * @var boolean
     */
    public $promo = false;


}