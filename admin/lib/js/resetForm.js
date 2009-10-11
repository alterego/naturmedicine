function resetForm(){
  var obj = document.forms["form_new"];
  for(i = 0; i < obj.elements.length; i++){
     if(obj.elements[i].type == "text"){
       obj.elements[i].value = "";
     }      
     if (obj.elements[i].type == "textarea"){          
       tinyMCE.getInstanceById(obj.elements[i].name).getBody().innerHTML = "";
     }
  }
  obj.elements["id"].value = "";
  document.getElementById("formular_fehler").innerHTML = "";
  $("anker").scrollTo();
}