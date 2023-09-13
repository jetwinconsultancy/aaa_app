<?php
  function negative_bracket($number)
  {
      if($number == 0)
      {
          return "-";
      }
      elseif($number < 0)
      {
          return "(" . number_format(abs($number), 2) . ")";
      }
      else
      {
          return number_format($number, 2);
      }
  }
?>