window.addEventListener('load', bindEvents)

function bindEvents()
{
    let buttons = document.querySelectorAll("button")
    for (let button of buttons) {
        let link = button.getAttribute('href')
        if (link) {
            button.addEventListener('click', () => window.location = link)
        }
    }
}