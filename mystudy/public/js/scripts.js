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

document.getElementById('lecturers_photo').addEventListener('change', showFile, false);



