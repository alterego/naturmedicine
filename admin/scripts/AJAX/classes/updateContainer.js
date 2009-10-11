function update(script,element)
{
	  new Ajax.Updater(element,script, {
		  method: 'get'
	  });
}