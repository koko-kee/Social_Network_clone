function previewImage(event) {
    var input = event.target;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imagePreview = document.getElementById('text-center');
            imagePreview.style.height = "400px";
            imagePreview.innerHTML = '<img style="width:100%;height:100%; object-fit: cover;" src="' + e.target.result + '" alt="Preview Image">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}