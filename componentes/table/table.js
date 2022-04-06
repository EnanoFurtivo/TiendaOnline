const checkTableScrollbar = function(tableElement) 
{
  var tbody = document.getElementById(tableElement).getElementsByTagName('tbody')[0];
  var spacer = $('#'+tableElement+'').find('.scrollbar-spacer')[0];

  if (tbody.clientHeight < tbody.scrollHeight)
    spacer.hidden = false;
  else
    spacer.hidden = true;
}

const esconderTableScrollbar = function(tableElement) 
{
  var spacer = $('#'+tableElement+'').find('.scrollbar-spacer')[0];
  spacer.hidden = true;
}

const eliminarTableContent = function(tableElement) 
{
  var table = document.getElementById(tableElement);
  var tbody = table.getElementsByTagName('tbody')[0];
  
  tbody.innerHTML = "";

  if (table.classList.contains('scrollable-table'))
    esconderTableScrollbar(tableElement);
}

const setTablecontent = function(tableElement, content)
{
  var table = document.getElementById(tableElement);
  var tbody = table.getElementsByTagName('tbody')[0];
  
  tbody.innerHTML = content;

  if (table.classList.contains('scrollable-table'))
    checkTableScrollbar(tableElement);

  if (table.classList.contains('sortable'))
    $.bootstrapSortable();
}