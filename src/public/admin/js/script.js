const body = document.querySelector('body'),
    sidebar = body.querySelector('nav'),
    subs = body.querySelectorAll('.sub-close'),
    submenu = body.querySelector('.submenu-close'),
    toggle = body.querySelector(".toggle"),
    searchBtn = body.querySelector(".search-box"),
    modeSwitch = body.querySelector(".toggle-switch"),
    modeText = body.querySelector(".mode-text");


// toggle.addEventListener("click", () => {
//     sidebar.classList.toggle("close-sidebar");
// })

subs.forEach(async (sub) => {
    sub.addEventListener("click", (e) => {
        if (sub.classList.contains("sub-close")) {
            sub.classList.add('sub-open')
            sub.classList.remove('sub-close')
        } else {
            sub.classList.remove('sub-open')
            sub.classList.add('sub-close')
        }
    });
});

// modeSwitch.addEventListener("click", () =>{
//     body.classList.toggle("dark");

//     if(body.classList.contains("dark")){
//         modeText.innerText = "День";
//     }else{
//         modeText.innerText = "Ночь";
//     }
// });

document.addEventListener('DOMContentLoaded', () => {

    const ajaxSend = async (formData, formAction) => {
        const fetchResp = await fetch(formAction, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            method: 'POST',
            body: formData
        });
        if (!fetchResp.ok) {
            throw new Error(`Ошибка по адресу ${url}, статус ошибки ${fetchResp.status}`);
        }
        return await fetchResp.text();
    };

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let fetchTorF = this.dataset.fetch
            this.querySelector('button[type="submit"]').disabled = true;

            if (fetchTorF == 'yes') {
                e.preventDefault();
                //this.disabled = true;
                const formData = new FormData(this);
                // alert(formData)
                const formAction = this.getAttribute('action');
                const formMethod = this.getAttribute('method');
                if (formAction) {
                    ajaxSend(formData, formAction)
                        .then((response) => {
                            var data = JSON.parse(response);
                            console.log(data);
                            //$(this).children('.notices').addClass('done');
                            //this.classList.add("my-class");
                            //form.reset(); // очищаем поля формы
                            if (data.action == 'delTable') {
                                let tr = document.getElementById(data.tr);
                                tr.parentNode.removeChild(tr);
                                document.querySelector('section.wrapper').classList.remove("modalActive")
                                document.querySelector('.modal').classList.remove("active")
                            }
                            if (data.action == 'editCheckProxy') {
                                document.querySelector('section.wrapper').classList.remove("modalActive")
                                document.querySelector('.modal').classList.remove("active")
                                document.querySelector('.bodyFirst').style.display = 'block';
                                document.querySelector('.bodyEdit').style.display = 'none';
                                document.querySelector('.bodyEdit .proxis').innerHTML = '';
                            }
                            if (data.action == 'sending') {
                                document.querySelector('.sendTextarea').value = '';
                                document.querySelector('.sendContent').innerHTML = '';
                            }
                            // if (data.action == 'closeSupp') {
                            //     window.location.replace("http://proxy.ssda.gq/admin/support/");
                            // }

                            this.querySelector('button[type="submit"]').disabled = false;
                        })
                        .catch((err) => console.error(err)) 

                }
            }
        });
    });

});





