function checkedall(checked)
{
  for (var i = 0; i < document.forms["eintraege"].elements.length; i++) {
    document.forms["eintraege"].elements[i].checked = checked;
  }
}