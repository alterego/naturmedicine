function ajaxRequest(url) {
  new Ajax.Request(url,
    {
      method:'get',
      onSuccess: 
    	function(transport,json){
          if(json.text == "open"){
            $(json.formname).style.display = 'block';
            $('invisible_'+json.formname).style.display = 'block';
          }
          if(json.text == "close"){
            $(json.formname).style.display = 'none';
          }          
        },
      onFailure: function(){ alert('Die Aktion konnte nicht durchgeführt werden!') }
    });
}