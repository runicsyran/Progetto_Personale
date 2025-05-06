function validatePassword(form) {
    event.preventDefault();
    var password = document.getElementById("password").value;
    var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

    if (!regex.test(password)) {
        // alert("La password deve contenere almeno 8 caratteri, una lettera maiuscola, una lettera minuscola e un numero.");
        const myPopup = new Popup({
            id: "popup1",
            title: "Password non valida",
            content: "inserisci una password valida, che contenga almeno una lettera maiuscola, una lettera minucola e un numero",  
            backgroundColor: "skyblue",
        });
        myPopup.show();
        return false;
    }
    form.submit();
    return true;
}