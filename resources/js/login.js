const form = document.querySelector(".login form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{
    e.preventDefault();
}

continueBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/login.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          //if(xhr.status === 200){
            let data = JSON.parse(xhr.response);
            console.log(data);
            if(data.code === 200) {
              location.href="users.php";
            } else {
              errorText.style.display = "block";
              let error_message = '<ul>';
              for(var i = 0; i < data.description.length; i++) {
                  error_message += '<li>' + data.description[i] + '</li>';
                if(i + 1 == data.description.length) {
                  errorText.innerHTML = error_message + '</ul>';

                }
              }
            }
          }
      //}
    }
    let formData = new FormData(form);
    xhr.send(formData);
}