function negative_bracket_js(number)
{
  if(parseFloat(number) == 0 || number == '')
  {
      return "-";
  }
  else if(parseFloat(number) < 0)
  {
      return "(" + numeral(Math.abs(parseFloat(number))).format('0,0') + ")";
  }
  else
  {
      return numeral(parseFloat(number)).format('0,0');
  }
}

function string_to_number(number)
{
  number = parseFloat(number);

  if(isNaN(number) || number == '')
  {
    return 0;
  }
  else
  {
    return number;
  }
}

function negative_bracket_to_number(number)
{
  var ori_num = number;

  if(number == '')
  {
    number = 0;
  }
  else
  {
    number = number.replaceAll(',', '');
    number = number.replace('(', '-');
    number = number.replace(')', '');

    if(isNaN(parseInt(number)) || number == '-')
    {
      number = 0;
    }
  }

  return parseInt(number);
}