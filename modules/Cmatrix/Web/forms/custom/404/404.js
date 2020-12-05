let Content = document.querySelector('.exception .message');
let Show = document.querySelector('.show');
let Hide = document.querySelector('.hide');

Show.addEventListener("click", () => {
    //content.style.display = "block";
    Content.classList.toggle('hidden');
    Show.classList.toggle('hidden');
    Hide.classList.toggle('hidden');
});

Hide.addEventListener("click", () => {
    //content.style.display = "none";
    Content.classList.toggle('hidden');
    Show.classList.toggle('hidden');
    Hide.classList.toggle('hidden');
});


/*
let content = document.getElementById("content");
let show = document.getElementById("showContent");
let hide = document.getElementById("hideContent");

show.addEventListener("click", () => {
    content.style.display = "block";
});

hide.addEventListener("click", () => {
    content.style.display = "none";
});
*/