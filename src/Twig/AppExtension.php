<?php

namespace App\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('value_percent', array($this, 'valuePercent')),
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

    //todo doing function and not use filter
    public function valuePercent($value)
    {
        if($value > 0){
            $class = "text-navy";
            $level = "fa-level-up";
        }elseif($value < 0){
            $class = "text-danger";
            $level =  "fa-level-down";
        }else{
            $class = "text-success";
            $level =  "";
        }

        return '<div class="'.$class.'"<i class="fa '.$level.'"></i>'.$value.' %</div>';
    }
}
