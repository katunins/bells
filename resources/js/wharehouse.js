
window.changeFilter = function () {
    window.filter.themes = []
    window.filter.mission = []
    document.querySelectorAll('input[name="checkFilter"]').forEach(el => {
        if (el.checked) {
            window.filter[el.getAttribute('theme')].push(el.value)
        }
    })

    buildScreen(1)
}

window.clearFilter = function () {
    document.querySelectorAll('input[name="checkFilter"]').forEach(el => {
        el.checked = false
    })
    window.filter = {
        title: '',
        themes: [],
        mission: [],
    }
    buildScreen(1)
}

// рендерит кнопки страниц
//             currentPage:result.currentPage, 
//             pageQuantity:result.pageQuantity
window.renderPagination = function (data) {
    if (data.pageQuantity <= 1) return
    let buttonsHtml = ''
    for (let index = 1; index <= data.pageQuantity; index++) {
        buttonsHtml += '<li><button '
        if (index == data.currentPage) buttonsHtml += 'class="active-pagination" '
        buttonsHtml += 'page="' + index + '" '
        buttonsHtml += 'onclick="buildScreen(' + index + ')">'
        buttonsHtml += index
        buttonsHtml += '</button></li>'
    }
    if (data.currentPage < data.pageQuantity) {
        buttonsHtml += '<li><button onclick="buildScreen(0, 1)">Следующая</button></li>'
    }

    let table = document.querySelector('.pagination-block')

    let pagination = document.querySelector('ul.pagination')
    if (pagination === null) {
        pagination = document.createElement('ul')
    }

    pagination.className = 'pagination'
    pagination.setAttribute('maxPage', data.pageQuantity)

    pagination.innerHTML = buttonsHtml
    table.appendChild(pagination)

}


// ввод в поисковое поле
window.newSearch = function (reset = false) {
    if (typeof (cloneInputText) === "function") cloneInputText();
    let titleFilter = event.target.value
    if (reset) titleFilter = ''
    window.filter.title = titleFilter
    buildScreen(1, false)
}

// получает экран товаров - page страницу
// shift - нажата кнопка Следующая
window.buildScreen = function (page, shift = false) {
    if (shift != false) page = Number(document.querySelector('.active-pagination').getAttribute('page')) + shift
    ajax('getWharehouse', {
        page: page,
        filter: window.filter
    }, function (result) {
        renderPage(result.data)
        renderPagination({
            currentPage: result.currentPage,
            pageQuantity: result.pageQuantity
        })
    })
}

document.addEventListener('DOMContentLoaded', function () {
    clearFilter()
})