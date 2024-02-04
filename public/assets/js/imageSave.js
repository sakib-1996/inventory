//======= show image in the page after select
function previewImage(inputId, containerId, width, height) {
    var input = document.getElementById(inputId);
    var previewContainer = document.getElementById(containerId);
    var previewImage = document.createElement("img");

    while (previewContainer.firstChild) {
        previewContainer.removeChild(previewContainer.firstChild);
    }

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;

            // Set width and height based on parameters
            previewImage.style.width = width + "px";
            previewImage.style.height = height + "px";

            previewContainer.appendChild(previewImage);
        };

        reader.readAsDataURL(input.files[0]);
    }
} // use it in the input fild to call onchange="previewImage('dynamicCategoryId', 'dynamicPreviewContainerId', 200, 150)"




//======= Check if the file type is among accepted types
function validateFileType(file) {
    var acceptedTypes = ["image/jpeg", "image/png", "image/jpg"];
    if (acceptedTypes.indexOf(file.type) === -1) {
        return false;
    }
    return true;
}



//======= Check if the file size is within the allowed limit (2048 KB)
function validateFileSize(file) {
    var maxSizeKB = 2048;
    if (file.size > maxSizeKB * 1024) {
        return false;
    }
    return true;
}
