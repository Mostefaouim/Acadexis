function showAlert() {
    var civility = document.querySelector('input[name="civility"]:checked');
    var civilityValue = civility ? civility.value : "";
    var name = document.getElementById("nom").value;
    var address = document.getElementById("adresse").value;
    var postalCode = document.getElementById("NoPostal").value;
    var locality = document.getElementById("localite").value;
    var country = document.getElementById("Pays").value;
    var platforms = Array.from(document.querySelectorAll('input[name="platforms[]"]:checked')).map(checkbox => checkbox.value).join(', ');
    var applications = Array.from(document.getElementById("Applications").options).filter(option => option.selected).map(option => option.value).join(', ');

    var message = "Votre Civilité est : " + civilityValue + "\n" +
        "Votre Nom est : " + name + "\n" +
        "Votre Adresse : " + address + "\n" +
        "Votre Code Postal : " + postalCode + "\n" +
        "Votre Localité : " + locality + "\n" +
        "Votre Pays : " + country + "\n" +
        "Vos Plate-formes : " + platforms + "\n" +
        "Vos Applications : " + applications;

    alert(message);
}

function uploadImage() {
    var input = document.getElementById("imageInput");
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imageUrl = e.target.result;
            var imageElement = document.createElement("img");
            imageElement.src = imageUrl;
            document.getElementById("imageContainer").appendChild(imageElement).style.width = "350px";
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function clearForm(event) {
    var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement ?");
    if (confirmation) {
        var form = document.getElementById('studentForm');
        form.reset();
        document.getElementById('h3').style.display = "none";
        document.getElementById('imagePreview').style.display = "none";
        document.getElementById('numero').value = '';
        document.getElementById('nom').value = '';
        document.getElementById('adresse').value = '';
        document.getElementById('localite').value = '';
        document.getElementById('NoPostal').value = '';
        document.getElementById('Pays').value = '';
        document.getElementById('monsieur').checked = false;
        document.getElementById('madame').checked = false;
        document.getElementById('mademoiselle').checked = false;
        var platforms = document.getElementsByName('platforms[]');
        platforms.forEach(function(platform) {
            platform.checked = false;
        });
        var applications = document.getElementById('applications');
        for (var i = 0; i < applications.options.length; i++) {
            applications.options[i].selected = false;
        }
        var sports = document.getElementById('sports');
        for (var i = 0; i < sports.options.length; i++) {
            sports.options[i].selected = false;
        }

    } else {
        event.preventDefault();
    }
}