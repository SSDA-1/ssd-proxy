$(function () {
    $(document).on("click", ".mobile_menu_container .parent", function (e) {
        e.preventDefault();
        $(".mobile_menu_container .activity").removeClass("activity");
        $(this).siblings("ul").addClass("loaded").addClass("activity");
    });
    $(document).on("click", ".mobile_menu_container .back", function (e) {
        e.preventDefault();
        $(".mobile_menu_container .activity").removeClass("activity");
        $(this).parent().parent().removeClass("loaded");
        $(this).parent().parent().parent().parent().addClass("activity");
    });
    $(document).on("click", ".mobile_menu", function (e) {
        e.preventDefault();
        $(".mobile_menu_container").addClass("loaded");
        $(".mobile_menu_overlay").fadeIn();
    });
    $(document).on("click", ".mobile_menu_overlay", function (e) {
        $(".mobile_menu_container").removeClass("loaded");
        $(this).fadeOut(function () {
            $(".mobile_menu_container .loaded").removeClass("loaded");
            $(".mobile_menu_container .activity").removeClass("activity");
        });
    });
})

function openModal(title, massage, status) {
    document.querySelector('.modal.notifications .title').innerHTML = title;
    document.querySelector('.modal.notifications .massage').innerHTML = massage;
    document.querySelector('.modal.notifications').classList.add("active")
    document.querySelector('.js-overlay-modal').classList.add("active")
    const elements = document.querySelectorAll('.fa.fa-exclamation-triangle');
    elements.forEach(element => {
        element.style.display = 'inline-block';
    });
    if (status && status != 'process') {
        document.querySelector('.modal.notifications .textWrap').classList.add("done")
    } else if (status != 'process') {
        document.querySelector('.modal.notifications .textWrap').classList.add("error")
        document.querySelector('.modal.notifications .buttonFormWrap').style.display = 'flex';
    } else if (status == false) {
        document.querySelector('.modal.notifications .textWrap').classList.add("error");
        document.querySelector('.modal.notifications .massage').innerHTML = massage;
    } else {
        const elements = document.querySelectorAll('.fa.fa-exclamation-triangle');
        elements.forEach(element => {
            element.style.display = 'none';
        });
        document.querySelector('.modal.notifications .buttonFormWrap').style.display = 'none';
    }
    if (title == 'Покупка') {
        const element = document.querySelector('.modal.notifications .textWrap');
        const loadingElement = document.createElement('div');
        loadingElement.setAttribute('class', 'loadingProcess');
        loadingElement.setAttribute('style', 'width: 50px;');
        loadingElement.innerHTML = `
        <span class="buy-timer"><img src="/assets/img/buy-timer.gif"></span>
        `;
        element.appendChild(loadingElement);
    }
};
function openModalChecker(data, id) {
    const amountElem = document.querySelector('.modal.crypt #amountUSDTChecker');
    const adressElem = document.querySelector('.modal.crypt #adressUSDTChecker');
    if (amountElem) {
        amountElem.textContent = data.amount_for_pay;
    }
    if (adressElem) {
        adressElem.textContent = data.wallet;
    }
    document.querySelector('.modal.crypt').classList.add("active")
    const qrElem = document.getElementById('qrCode');
    const qrCode = new QRCode(document.querySelector('.qrCode'), {
        text: data.wallet,
        width: 128,
        height: 128,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    const intervalId = setInterval(async () => {
        const response = await fetch('/usdtchecker/check/' + id);
        const responseData = await response.json();
        if (responseData.success === true) {
            clearInterval(intervalId);
            document.querySelector('.modal.crypt .done').classList.add("active");
        }
    }, 2000);
};

document.addEventListener("click", function (e) {
    if (e.target.classList.contains('background') || $(e.target).is('.closeModal')) {
        document.querySelector('.modal').classList.remove("active")
        document.querySelector('.modal.extend').classList.remove("active")
    }
});

const ajaxSend = async (formData, formAction) => {
    const fetchResp = await fetch(formAction, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        body: formData
    });
    return await fetchResp.text();
    if (!fetchResp.ok) {
        throw new Error(`Ошибка по адресу , статус ошибки ${fetchResp.status}`);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    let buysCheck = 0;
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let fetchTorF = this.dataset.fetch

            if (fetchTorF !== 'none') {
                e.preventDefault();
                const formData = new FormData(this);
                const formAction = $(this).attr('action');
                const formGetting = $(this).attr('data-getting');
                if (formAction) {
                    if (formGetting == 'buy') {
                        openModal('Покупка', '', 'process');
                    }
                    ajaxSend(formData, formAction)
                        .then((response) => {
                            var data = JSON.parse(response);
                            console.log(data);
                            console.log(data.massage);
                            if (data.tut) {
                                let rotationAllText = form.querySelector('.textWrap .massage p');
                                let rotationAllBtn = form.querySelector('.buttonFormWrap');
                                let rotationAllBtnOld = rotationAllBtn;
                                rotationAllText.textContent = data.tut;
                                rotationAllBtn.remove();
                                
                                setTimeout(function () {
                                    form.appendChild(rotationAllBtnOld);
                                    // document.querySelector('.js-overlay-modal').remove('active');
                                    // form.parentElement.parentElement.parentElement.classList.remove('active');
                                }, 1000); 
                            }
                            if (document.querySelector('.loadingProcess')) {
                                document.querySelector('.loadingProcess').remove();
                                if (data.status == false) {
                                    openModal('Покупка не завершена', data.massage, data.status);
                                }
                            }

                            if (data.errors && data.errors.telegram_chat_id) {
                                document.querySelector('.error-tg').innerHTML = data.errors.telegram_chat_id[0] == 'The telegram chat id must be a number.' ? 'Внимательно прочтите инструкцию выше!<br>Тут должен быть ChatID а не ваш НИК!' : '';
                            }

                            $(this).children('.notices').addClass('done');
                            form.reset();

                            if (data.url) {
                                window.location.replace(data.url);
                            }

                            if (data && data.data && data.data.location !== undefined) {
                                window.location.replace(data.data.location);
                            }

                            if (data.operation == 'sending') {
                                document.querySelector('.sendTextarea').value = '';
                                document.querySelector('.sendContent').innerHTML = '';
                            }

                            if (data.operation == 'payment') {
                                let historyOp = document.createElement('div');
                                historyOp.setAttribute("class", "block-history");
                                let elemAfter = document.querySelector('.title-balance-history');
                                historyOp.innerHTML = '<div class="history-date">' + data.date + '</div><div class="sum">+' + data.amount + ' рублей</div>';
                                elemAfter.parentNode.insertBefore(historyOp, elemAfter.nextSibling);
                            }

                            if (data.filesettingssite != null && data.filesettingssite == 'icon') {
                                document.getElementById('iconFile').remove();
                                document.querySelector('[data-type="icon"]').remove();
                            } else if (data.filesettingssite != null && data.filesettingssite == 'logo') {
                                document.getElementById('logoFile').remove();
                                document.querySelector('[data-modal="del"]').remove();
                            }
                            if (data.modal) {
                                if (data.status == true) {
                                    buysCheck = 1;
                                    document.querySelector('.modal.extend').classList.remove("active")
                                    openModal(data.title, data.massage, data.status);

                                    if (data.massage == 'Прокси успешно приобретён и добавлен в ваш профиль') {
                                        setTimeout(function () {
                                            var timestamp = new Date().getTime();
                                            window.location.href = "/control-panel" + "?timestamp=" + timestamp;
                                        }, 3000);
                                    } else if (data.massage == 'Автопродление включено') {
                                        document.querySelector('.modal.autorenewal').classList.remove("active");
                                        document.getElementById('proxy' + data.id).querySelector('.autopayButt').classList.add('active')
                                    }
                                } else if (data.status == false) {
                                    openModal('Покупка не завершена', data.massage, data.status);
                                }
                                if (data.operation != 'usdtchecker') {

                                } else {
                                    document.querySelector('.modal.crypt .done').classList.remove("active");
                                    openModalChecker(data.data, data.data.idtransaction)
                                }
                            }
                        })
                        .catch((err) => console.error(err))
                    const timeoutPromise = new Promise(resolve => setTimeout(() => resolve('No response from server'), 10000));
                    Promise.race([ajaxSend, timeoutPromise])
                        .then(result => {
                            if (result instanceof Response) {
                                console.log('Response: ', result.status);
                            } else {
                            }
                        })
                        .catch(error => console.error('Error: ', error))
                        .finally(() => {
                            if (!document.querySelector('.textWrap').classList.contains('done')) {
                                console.log('Покупка не завершена');
                            }
                        });
                }
            }
        });
    });

});
