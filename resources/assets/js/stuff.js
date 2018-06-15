document.addEventListener('DOMContentLoaded', () => {
    // auto resize textareas
    let textareas = document.getElementsByTagName('textarea')
    if(textareas.length) {
        for(let i = 0; i < textareas.length; i++) {
            textareas[i].addEventListener('keydown', () => {
                let windowTop = window.pageYOffset
                let windowLeft = window.pageXOffset
                textareas[i].style.height = 'auto'
                textareas[i].style.height = textareas[i].scrollHeight + 'px'
                window.scrollTo(windowLeft, windowTop)
            })
        }
    }

    // navbar stuff
    let $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0)
    if($navbarBurgers.length > 0) {
        $navbarBurgers.forEach($el => {
            $el.addEventListener('click', () => {
                let target = $el.dataset.target
                let $target = document.getElementById(target)
                $el.classList.toggle('is-active')
                $target.classList.toggle('is-active')
            });
        });
    }

})
