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
    let inputs = document.querySelectorAll('.form-group input')
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
})
