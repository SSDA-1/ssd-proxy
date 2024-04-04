// ===== Modal delete =====
let butts = document.querySelectorAll('[data-modal="del"]');
butts.forEach(butt => {
    butt.addEventListener("click", function () {
        let title = this.dataset.title;
        document.getElementById('modalFormExport').style.display = 'none';
        document.querySelector('.modal .title').innerHTML = title;
        document.querySelector('section[class="wrapper"]').classList.add("modalActive")
        document.querySelector('.modal').classList.add("active")
        document.getElementById('modalForm').action = this.dataset.action;
        document.getElementById('modalForm').style.display = 'block'
        
        document.getElementById('modalDesc').style.display = 'none'
        if (this.dataset.fetch) {
            document.getElementById('modalForm').dataset.fetch = this.dataset.fetch;
            // console.log("Кнопка нажата.");
        }
    });
});
// ===== Modal export ports =====
let buttsPort = document.querySelectorAll('[data-modal="exportports"]');
buttsPort.forEach(buttPort => {
    buttPort.addEventListener("click", function () {
        let title = this.dataset.title;
        document.getElementById('modalForm').style.display = 'none';
        document.querySelector('.modal .title').innerHTML = title;
        document.querySelector('section[class="wrapper"]').classList.add("modalActive")
        document.querySelector('.modal').classList.add("active")
        document.getElementById('modalFormExport').action = this.dataset.action;
        // Манипуляции с Сервером
        document.getElementById('modalDesc').style.display = 'block'
        const jsonString = buttPort.dataset.servers;
        const servers = JSON.parse(jsonString);
        const serversArray = Array.from(servers); // преобразование в массив
        const firstValue = Object.entries(servers)[0][1];
        document.getElementById("serverHiddenModal").value = firstValue;
        // Получаем ссылку на элемент select
        const select = document.getElementById("selectServerExport");
        // Используем цикл for...of для перебора элементов массива серверов
        for(let server in servers){
            // Создаем новый option для текущего сервера и добавляем его в select
            const option = document.createElement("option");
            option.value = servers[server];
            option.text = server;
            select.add(option);
        };
        
        if (this.dataset.fetch) {
            document.getElementById('modalFormExport').dataset.fetch = this.dataset.fetch;
            // console.log("Кнопка нажата.");
        }
    });
});
// ===== Modal export proxy =====
let buttsProxy = document.querySelectorAll('[data-modal="exportproxy"]');
buttsProxy.forEach(buttProxy => {
    buttProxy.addEventListener("click", function () {
        let title = this.dataset.title;
        document.getElementById('modalForm').style.display = 'none';
        document.querySelector('.modal .title').innerHTML = title;
        document.querySelector('section[class="wrapper"]').classList.add("modalActive")
        document.querySelector('.modal').classList.add("active")
        document.getElementById('modalFormExport').action = this.dataset.action;
        // Манипуляции с Сервером
        document.getElementById('modalDesc').style.display = 'block'
        const jsonString = buttProxy.dataset.servers;
        const servers = JSON.parse(jsonString);
        const serversArray = Array.from(servers); // преобразование в массив
        const firstValue = Object.entries(servers)[0][1];
        document.getElementById("serverHiddenModal").value = firstValue;
        // Получаем ссылку на элемент select
        const select = document.getElementById("selectServerExport");
        // Используем цикл for...of для перебора элементов массива серверов
        for(let server in servers){
            // Создаем новый option для текущего сервера и добавляем его в select
            const option = document.createElement("option");
            option.value = servers[server];
            option.text = server;
            select.add(option);
        };
        if (this.dataset.fetch) {
            document.getElementById('modalFormExport').dataset.fetch = this.dataset.fetch;
            // console.log("Кнопка нажата.");
        }
    });
});
// ===== Modal export Users =====
let buttsUsers = document.querySelectorAll('[data-modal="exportusers"]');
buttsUsers.forEach(buttUsers => {
    buttUsers.addEventListener("click", function () {
        let title = this.dataset.title;
        document.getElementById('modalForm').style.display = 'none';
        document.querySelector('.modal .title').innerHTML = title;
        document.querySelector('section[class="wrapper"]').classList.add("modalActive")
        document.querySelector('.modal').classList.add("active")
        document.getElementById('modalFormExport').action = this.dataset.action;
        if (this.dataset.fetch) {
            document.getElementById('modalFormExport').dataset.fetch = this.dataset.fetch;
            // console.log("Кнопка нажата.");
        }
    });
});

// ===== Modal Редактирование Прокси Массовое =====
let buttsEditCheckedProxy = document.querySelectorAll('[data-modal="editcheckproxy"]');
buttsEditCheckedProxy.forEach(buttProxy => {
    buttProxy.addEventListener("click", function () {
        let title = this.dataset.title;
        const checkedBoxes = document.querySelectorAll('.proxy-checkbox:checked');
        document.getElementById('modalForm').style.display = 'none';
        document.querySelector('.modal .bodyEdit .title').innerHTML = title;
        document.querySelector('.bodyFirst').style.display = 'none';
        document.querySelector('.bodyEdit').style.display = 'block';
        
        document.getElementById('modalDesc').style.display = 'none'
        // 
         // Создаем элементы формы модального окна и добавляем туда отмеченные чекбоксы
        const form = document.getElementById('modalFormEdit');
        // const daysLabel = document.createElement('label');
        // const daysInput = document.createElement('input');
        // const timeLabel = document.createElement('label');
        // const timeInput = document.createElement('input');
        
        // // Устанавливаем атрибуты для элементов формы
        // daysLabel.setAttribute('for', 'days');
        // daysLabel.innerText = 'Дни:';
        // daysInput.setAttribute('type', 'text');
        // daysInput.setAttribute('id', 'days');

        // timeLabel.setAttribute('for', 'time');
        // timeLabel.innerText = 'Время:';
        // timeInput.setAttribute('type', 'text');
        // timeInput.setAttribute('id', 'time');

        // Добавляем отмеченные чекбоксы в скрытое поле формы
        checkedBoxes.forEach(function(checkbox) {
            const input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', 'selected-proxies[]');
            input.setAttribute('value', checkbox.value);
            form.querySelector('.proxis').appendChild(input);
        });

        // Добавляем элементы формы в модальное окно
        // form.querySelector('.mass').appendChild(daysLabel);
        // form.querySelector('.mass').appendChild(daysInput);
        // form.querySelector('.mass').appendChild(timeLabel);
        // form.querySelector('.mass').appendChild(timeInput);
        // // Добавляем форму в элемент с классом `modal` и `mass`
        
        document.querySelector('section[class="wrapper"]').classList.add("modalActive")
        document.querySelector('.modal').classList.add("active")
        document.getElementById('modalFormExport').action = this.dataset.action;
        if (this.dataset.fetch) {
            document.getElementById('modalFormExport').dataset.fetch = this.dataset.fetch;
            // console.log("Кнопка нажата.");
        }
    });
});

// Тут закрытие модального окна
document.addEventListener("click", function (e) {
    if (e.target.classList.contains('background')) { // || $(e.target).is('#buttomModal')
        document.querySelector('section.wrapper').classList.remove("modalActive")
        document.querySelector('.modal').classList.remove("active")
        
        document.querySelector('.bodyFirst').style.display = 'block';
        document.querySelector('.bodyEdit').style.display = 'none';
        document.querySelector('.bodyEdit .proxis').innerHTML = '';
    }
});

// select
// Получаем ссылку на селект и инпут
const selectServer = document.getElementById("selectServerExport");
const inputServer = document.getElementById("serverHiddenModal");

// Слушаем событие изменения значения селекта
selectServer.addEventListener("change", function() {
  // Получаем выбранную опцию селекта и её значение
  const selectedOption = this.options[this.selectedIndex];
  const selectedValue = selectedOption.value;

  // Устанавливаем значение в инпут
  inputServer.value = selectedValue;
});
