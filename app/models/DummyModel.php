<?php
/**
 * Created by IntelliJ IDEA.
 * User: matko
 * Date: 1/27/16
 * Time: 2:03 PM
 */

namespace app\models;


class DummyModel
{
    private $dataInString = "Pearl Phelps
Mui Mcmahon
Victoria Vining
Lachelle Leamon
Phuong Pinto
Jodi Johnstone
Cathey Conerly
Cyndy Couch
Estrella Evins
Asley Aguilera
Nancee Noe
Shad Sorrels
Marva Martell
Palma Poulos
Roxie Rosenstein
Sherry Strittmatter
Jacqui Jurado
Julene Jack
Dion Depp
Billie Bibb";


    var $data = array();

    public function __construct()
    {
        $wholeNames = explode("\n", $this->dataInString);
        foreach ($wholeNames as $wholeName) {
            $this->data[] = explode(" ", trim($wholeName));
        }
    }
}