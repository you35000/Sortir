let btns = document.querySelectorAll(".update");
btns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    let id = btn.id.substring(6, 8);
    let input = document.getElementById("campusName" + id);
    input.removeAttribute("readonly");
    input.classList.remove("form-control-plaintext");
    input.classList.add("form-control");
  });
});
