let mode;
if (idMode == 1) {
    body.classList.toggle("dark");
    modeText.innerText = "День";
} else {
    modeText.innerText = "Ночь";
    body.classList.remove("dark");
}
modeSwitch.addEventListener("click", (event) => {
    body.classList.toggle("dark");

    if (body.classList.contains("dark")) {
        modeText.innerText = "День";
        mode = 1;
    } else {
        modeText.innerText = "Ночь";
        mode = 0;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: modeUrl,
        type: "POST",
        data: {
            id: idUserMode,
            mode: mode,
        },
        success: function (response) {
            //  console.log(response);
        },
    });
    //  console.log(mode);
});

let sidebarMode;
if (sidebarIdMode == 0) {
    sidebar.classList.add("close-sidebar");
} else {
    sidebar.classList.remove("close-sidebar");
}

// Функция для обработки текста в ссылках
function processHeaderLinks() {
    const headerLinks = document.querySelectorAll('header .image-text .image a');
    console.log(headerLinks);
    
    
    headerLinks.forEach(link => {
        // Сохраняем оригинальный текст, если еще не сохранен
        if (!link.dataset.originalText) {
            link.dataset.originalText = link.textContent;
        }
        
        if (sidebar.classList.contains("close-sidebar")) {
            // Если сайдбар свернут, оставляем только первую и последнюю букву
            const text = link.dataset.originalText;
            if (text.length > 2) {
                link.textContent = text.charAt(0) + text.charAt(text.length - 1);
            }
        } else {
            // Если сайдбар развернут, возвращаем исходный текст
            link.textContent = link.dataset.originalText;
        }
    });
}

// Вызываем функцию при загрузке страницы
processHeaderLinks();

toggle.addEventListener("click", (event) => {
    sidebar.classList.toggle("close-sidebar");

    if (sidebar.classList.contains("close-sidebar")) {
        sidebarMode = 0;
    } else {
        sidebarMode = 1;
    }
    
    // Обрабатываем ссылки при изменении состояния сайдбара
    processHeaderLinks();
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: sidebarUrl,
        type: "POST",
        data: {
            id: idUserMode,
            sidebarMode: sidebarMode,
        },
        success: function (response) {
            // console.log(response);
        },
    });
    // console.log(sidebarMode);
});
