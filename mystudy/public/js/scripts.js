function showFile(e) {
    var file = e.target.files[0];
        var fr = new FileReader();
        fr.onload = (function(theFile) {
            return function(e) {
                document.getElementById('image').innerHTML = "<img src='" + e.target.result + "' width='100%' height='100%' />";
            };
        })(file);
        fr.readAsDataURL(file);
}

if(lecturers_photo = document.getElementById('lecturers_photo')){
    lecturers_photo.addEventListener('change', showFile, false);
}


if(news_image = document.getElementById('news_image')){
    news_image.addEventListener('change', showFile, false);
}

function toggle(el) {

    menu = el.nextElementSibling;
    btn_delete = el.lastElementChild;
    btn_edit = el.firstElementChild;

    if(menu.style.display == 'block'){
        menu.style.display = 'none';
        btn_delete.style.display = 'none';
        btn_edit.style.display = 'none';
    }
    else {
        menu.style.display = 'block';
        btn_delete.style.display = 'inline';
        btn_edit.style.display = 'inline';


       /* $(document).mouseup(function (e) {
            var container = $(menu);
            var container_delete = $(btn_delete);
            var container_edit = $(btn_edit);
             if (container.has(e.target).length === 0) {
                 container.hide();
                 container_delete.hide();
                 container_edit.hide();
            console.log(e.target.tagName);
            }
        });*/
    }

}

function hideMassage(massage) {
    console.log('hide');
  //  massage = document.getElementById('flash_massage');
    setInterval(massage.style.display='none',3000);
}


