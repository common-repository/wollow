var iconWa = document.querySelectorAll('.nav-link');
var button_icon = document.querySelectorAll('.button_icon');

var icon = [];
console.log(iconWa+' adk');
score = 16;
output = 0;
iconWa.forEach((item, index) => {
    item.addEventListener('click', e => {
        // e.preventDefault();

        e.target.classList.add("ijo");
        window.open(item.href, '_blank');
    })
    
})