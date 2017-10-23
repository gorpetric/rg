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

    // keep label up if input has value
    let inputs = document.querySelectorAll('.form-group-pretty input')
    if(inputs.length) {
        for(let i = 0; i < inputs.length; i++) {
            if(inputs[i].value !== '') {
                inputs[i].classList.add('has-value')
            } else {
                inputs[i].classList.remove('has-value')
            }
            inputs[i].addEventListener('focusout', () => {
                if(inputs[i].value !== '') {
                    inputs[i].classList.add('has-value')
                } else {
                    inputs[i].classList.remove('has-value')
                }
            })
        }
    }

    // dropdown menu
    const overlay = document.querySelector('.overlay')
    const navToggle = document.querySelector('.navigation .inner .nav-toggle')
    const navLinks = document.querySelector('.navigation .inner .links')

    let dropdowns = document.querySelectorAll('.navigation .inner .links .dropdown')
    if(dropdowns.length) {
        for(let i = 0; i < dropdowns.length; i++) {
            let dropdownInner = dropdowns[i].querySelector('.dropdown-inner')
            let dropdownToggle = dropdowns[i].querySelector('.dropdown-toggle')
            dropdownToggle.addEventListener('click', () => {
                dropdownInner.classList.toggle('is-showing')

                if(dropdownInner.classList.contains('is-showing')) {
                    if(!overlay.classList.contains('is-active')) overlay.classList.add('is-active')
                } else {
                    if(overlay.classList.contains('is-active')) overlay.classList.remove('is-active')
                }
            })
            overlay.addEventListener('click', () => {
                if(dropdownInner.classList.contains('is-showing')) dropdownInner.classList.remove('is-showing')
            })
        }
    }

    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('is-showing')

        if(navLinks.classList.contains('is-showing')) {
            if(!overlay.classList.contains('is-active')) overlay.classList.add('is-active')
        } else {
            if(overlay.classList.contains('is-active')) overlay.classList.remove('is-active')
        }
    })

    overlay.addEventListener('click', () => {
        if(navLinks.classList.contains('is-showing')) navLinks.classList.remove('is-showing')
        if(overlay.classList.contains('is-active')) overlay.classList.remove('is-active')
    })
})
