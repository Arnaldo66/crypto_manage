<?php
// src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
        );
    }

    public function priceFilter($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
    {
        if(substr(round($number,2),0,3) == '0.0'){
          $decimals = 4;
          if(substr(round($number,2),0,4) == '0.00'){
            $decimals = 6;
          }
        }
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);

        return $price;
    }
}
