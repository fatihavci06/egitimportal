/* const form = document.getElementById('chooseOne');
const divler = document.querySelectorAll('.none-div');

form.addEventListener('change', (event) => {
  // Tüm div'leri gizle
  divler.forEach(div => {
    div.style.display = 'none';
  });

  // Seçilen radio button'a göre ilgili div'i göster
  const secilenDivId = event.target.value + '-div';
  const secilenDiv = document.getElementById(secilenDivId);
  if (secilenDiv) {
    secilenDiv.style.display = 'block';
  }
}); */

var options = {
    selector: "#content", 
    height : "480",
    language_url: 'assets/plugins/custom/tinymce/langs/tr.js',
    language: 'tr',
    plugins: 'image, lists, advlist, code',
    toolbar: 'undo redo | styleselect | bold italic | link image | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code',
    images_upload_url: 'classes/addImageMce.php', 
    images_upload_base_path: '/assets/media/topics/',
    images_upload_credentials: true,
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData2;
    
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', 'classes/addImageMce.php');
    
        xhr.onload = function() {
          var json;
    
          if (xhr.status != 200) {
            failure('HTTP Error: ' + xhr.status);
            return;
          }
    
          json = JSON.parse(xhr.responseText);
    
          if (!json || typeof json.location != 'string') {
            failure('Invalid JSON: ' + xhr.responseText);
            return;
          }
    
          success(json.location);
        };
    
        formData2 = new FormData();
        formData2.append('file', blobInfo.blob(), blobInfo.filename());
    
        xhr.send(formData2);
      },

};

if ( KTThemeMode.getMode() === "dark" ) {
    options["skin"] = "oxide-dark";
    options["content_css"] = "dark";
}

tinymce.init(options);