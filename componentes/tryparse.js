const TryParseInt = function(str) {
  //Returns a parsed int or false if the parse fails//
  var retValue = false;
  
  if(typeof str === 'number')
    return str;

  if(str !== null) {
    if(str.length > 0) {
      if (!isNaN(str)) {
        retValue = parseInt(str);
      }
    }
  }

  return retValue;
}

const TryParseFloat = function(str) {
  //Returns a parsed float or false if the parse fails//
  var retValue = false;
  
  if(typeof str === 'number') 
    return str;

  if(str !== null) {
    if(str.length > 0) {
      if (!isNaN(str)) {
        retValue = parseFloat(str);
      }
    }
  }

  return retValue;
}