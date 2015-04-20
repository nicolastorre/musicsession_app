$(document).ready(function() {
  var input_tab = document.getElementsByTagName('input');
  for (i=0; i < input_tab.length; i++) {
      if(input_tab[i].type == "file") {
        input_tab[i].addEventListener('change', function(){
          // var fileUrl = window.URL.createObjectURL(this.files[0]);
          var fileUrl = this.files[0].name;
          this.style.display = "inline";
          var output = document.createElement('span');
          output.innerHTML = fileUrl;
          this.parentNode.parentNode.appendChild(output);
        });
      }
  }      
});
