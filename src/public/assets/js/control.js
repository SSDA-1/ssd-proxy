var checkboxAll = document.querySelector('.proxy-checkbox-all');
var checkboxes = document.querySelectorAll('.proxy-checkbox');
var valuesArray = [];
var idsInput = document.querySelectorAll('.ids');
var changeIpAll = document.getElementById('changeIpAll');
// var downloadAll = document.getElementById('downloadAll');
var btnP = document.querySelectorAll('.action-proxy .btn');


checkboxAll.addEventListener('change', function () {
  checkboxes.forEach(function (checkbox) {
    checkbox.checked = checkboxAll.checked;
  });
  updateValuesArray();
});

checkboxes.forEach(function (checkbox) {
  checkbox.addEventListener('change', function () {
    updateValuesArray();
  });
});

changeIpAll.addEventListener('click', function () {
  var valuesToSend = valuesArray;
  const formAction = "/fetch/multi/change/ip";
  const formData = new FormData();
  formData.append('ids', valuesToSend);
  ajaxSend(formData, formAction)
    .then((response) => {
      var data = JSON.parse(response);
      if (data.status) {
        const modalReset = document.querySelector('.modal.reset');
        const modalOverlay = document.querySelector('.overlay');
        modalReset.classList.add('active');
        modalOverlay.classList.add('active');
      }
    })
    .catch((err) => console.error(err))
});

// downloadAll.addEventListener('click', function () {
//   var valuesToSend = valuesArray;
//   const formAction = "/fetch/multi/download/time";
//   const formData = new FormData();
//   formData.append('ids', valuesToSend);
//   ajaxSend(formData, formAction)
//     .then((response) => {
//       var data = JSON.parse(response);
//     })
//     .catch((err) => console.error(err))
// });

function updateValuesArray() {
  valuesArray = [];
  checkboxes.forEach(function (checkbox) {
    if (checkbox.checked) {
      valuesArray.push(checkbox.value);
    }
  });
  btnP.forEach(function (btn) {
    if (valuesArray.length === 0) {
      btn.classList.add('disabled');
    } else {
      btn.classList.remove('disabled');
    }
  });
  idsInput.forEach(element => {
    element.value = valuesArray.join(', ');
  });
}
updateValuesArray()

var link = document.querySelector('.my-link');
if (link) {
  link.addEventListener('click', function (event) {
    event.preventDefault();
    proxy = event.target.dataset.proxy;
    proxy2 = event.target.dataset.proxy2;
    const formData = new FormData();
    const formAction = '/fetch/proxy/changeip/' + proxy + '/' + proxy2;
    ajaxSend(formData, formAction)
      .then((response) => {
        var data = JSON.parse(response),
          modalElem = document.querySelector('.modal[data-modal="reset"]'),
          overlay = document.querySelector('.js-overlay-modal'),
          closeButtons = document.querySelectorAll('.js-modal-close');
        if (data.status == true) {
          modalElem.classList.add('active');
          overlay.classList.add('active');
        }
      })
      .catch((err) => console.error(err))
  });
}
document.querySelectorAll('.reboot').forEach(button => {
  button.addEventListener('click', event => {
    modalElem = document.querySelector('.modal[data-modal="rebut"]'),
      overlay = document.querySelector('.js-overlay-modal'),
      closeButtons = document.querySelectorAll('.js-modal-close'),
      id = button.dataset.id;
    const formData = new FormData();
    const formAction = '/fetch/proxy/restart/' + id;
    ajaxSend(formData, formAction)
      .then((response) => {
        var data = JSON.parse(response);
        if (data.export == true) {
          modalElem.classList.add('active');
          overlay.classList.add('active');
        }
      })
      .catch((err) => console.error(err))
  });
});

function openModalExtend(id) {
  document.querySelector('.modal.extend').classList.add("active")
  document.querySelector('.modal.extend input[name="id"]').value = id;
};

function openModalAutorenewal(id) {
  var modal = document.querySelector('.modal.autorenewal');
  var currentAction = modal.querySelector('form').action
  document.querySelector('.js-overlay-modal').classList.add("active")
  modal.classList.add("active")
  var newAction = currentAction.replace('0', id);
  modal.querySelector('form').action = newAction;
  document.querySelector('.modal.autorenewal input[name="id"]').value = id;
};
document.querySelectorAll('.extendButt').forEach(extend => {
  extend.addEventListener('click', function (e) {
    let id = this.dataset.id;
    openModalExtend(id)
  })
});
document.querySelectorAll('.autopayButt').forEach(button => {
  button.addEventListener('click', event => {
    modalElem = document.querySelector('.modal[data-modal="rebut"]'),
      overlay = document.querySelector('.js-overlay-modal'),
      closeButtons = document.querySelectorAll('.js-modal-close'),
      id = button.dataset.id;
    if (button.classList.contains("active")) {
      button.classList.toggle("active")
      const formData = new FormData();
      const formAction = '/fetch/proxy/autopay/' + id;
      ajaxSend(formData, formAction)
        .then((response) => {
          var data = JSON.parse(response);
        })
        .catch((err) => console.error(err))
    } else {
      openModalAutorenewal(id)
    }
  });
});
var selects = document.querySelectorAll('.editPanelProxy select')
selects.forEach(select => {
  select.addEventListener('change', function () {
    let nameSelect = select.getAttribute('name'),
      selectedOption = select.options[select.selectedIndex].text,
      hrefReconnect = document.querySelector('.reconnect').dataset.href,
      idEdit = select.dataset.id
    const formData = new FormData();
    formData.append('id', idEdit);
    formData.append(nameSelect, select.value);
    document.querySelectorAll('.editPanelProxy select').forEach(selDisable => {
      selDisable.disabled = true;
    });
    document.querySelectorAll('.editPanelProxy .lds-facebook').forEach(lds => {
      lds.classList.add('active');
    });
    const formAction = '/fetch/save/proxy';
    ajaxSend(formData, formAction)
      .then((response) => {
        var data = JSON.parse(response);
        if (data.status == true) {
          document.querySelectorAll('.editPanelProxy select').forEach(selDisable => {
            selDisable.disabled = false;
          });
          document.querySelectorAll('.editPanelProxy .lds-facebook').forEach(lds => {
            lds.classList.remove('active');
          });
          document.querySelector('.openEdit + .editPanelProxy .statusSave').classList
            .add('active')
          setTimeout(() => {
            document.querySelector(
              '.openEdit + .editPanelProxy .statusSave').classList
              .remove('active')
          }, "2000")
          if (nameSelect == 'ifname') {
            document.querySelector('#proxy' + idEdit + ' .ifname').innerHTML =
              select.options[select.selectedIndex].text;
          } else if (nameSelect == 'reconnect_interval') {} 
          else if (nameSelect == 'reconnect_type') {
            if (selectedOption == 'time') {
              document.querySelector('#proxy' + idEdit + ' .reconnect')
                .innerHTML = 'По времени';
            } else if (selectedOption == 'link') {
              document.querySelector('#proxy' + idEdit + ' .reconnect')
                .innerHTML = 'По <a href="' + hrefReconnect + '">ссылке</a>';
            } else if (selectedOption == 'time_link') {
              document.querySelector('#proxy' + idEdit + ' .reconnect')
                .innerHTML = 'По времени и <a href="' + hrefReconnect +
                '">ссылке</a>';
            }

          }
        }
      })
      .catch((err) => console.error(err))
  });
});

const container = document.querySelector('.wrap-modal-proxy');
container.addEventListener('submit', event => {
  event.preventDefault();
  document.querySelector('.lds-ripple').style.display = 'inline-block'
  const form = event.target.closest('.settings-form');
  if (form) {
    const formData = new FormData(form);
    const formAction = '/fetch/save/proxy';

    ajaxSend(formData, formAction)
      .then((response) => {
        var data = JSON.parse(response);
        if (data.userChange === 'Такое имя пользователя занято - пожалуйста укажите другое.') {
          document.querySelector('.lds-ripple').style.display = 'none'
          document.querySelector('#errorUser').innerHTML = data.userChange;
          document.querySelector('.save-form').innerText = 'Ошибка';
          document.querySelector('.save-form').classList.add('false');
          setTimeout(function () {
            document.querySelector('.save-form').innerHTML =
              '<span class="lds-ripple"><span></span><span></span></span> Сохранить';
            document.querySelector('.save-form').classList.remove('false');
          }, 3000);
        } else {
          document.querySelector('.lds-ripple').style.display = 'none'
          document.querySelector('#errorUser').innerHTML = '';

          document.querySelector('.save-form').innerText = 'Сохранено';
          document.querySelector('.save-form').classList.add('true');
          setTimeout(function () {
            document.querySelector('.save-form').innerHTML =
              '<span class="lds-ripple"><span></span><span></span></span> Сохранить';
            document.querySelector('.save-form').classList.remove('true');
          }, 3000);
        }
      })
      .catch((err) => {
        console.error(err.response);
      })
  }
});

let inpEditP = document.querySelector('.editPanelProxy input');
if (inpEditP) {
  inpEditP.addEventListener('change', function () {
    let nameSelect = this.getAttribute('name'),
      selectedValue = this.value,
      idEdit = this.dataset.id
    const formData = new FormData();
    formData.append('id', idEdit);
    formData.append(nameSelect, this.value);
    document.querySelectorAll('.editPanelProxy select').forEach(selDisable => {
      selDisable.disabled = true;
    });
    document.querySelectorAll('.editPanelProxy .lds-facebook').forEach(lds => {
      lds.classList.add('active');
    });
    const formAction = '/fetch/save/proxy';
    ajaxSend(formData, formAction)
      .then((response) => {
        var data = JSON.parse(response);
        if (data.status == true) {
          document.querySelectorAll('.editPanelProxy select').forEach(selDisable => {
            selDisable.disabled = false;
          });
          document.querySelectorAll('.editPanelProxy .lds-facebook').forEach(lds => {
            lds.classList.remove('active');
          });
          document.querySelector('.openEdit + .editPanelProxy .statusSave').classList
            .add('active')
          setTimeout(() => {
            document.querySelector(
              '.openEdit + .editPanelProxy .statusSave').classList
              .remove('active')
          }, "2000")
        }
      })
      .catch((err) => console.error(err))
  });
}
document.addEventListener("click", function (e) {
  if (e.target.classList.contains('getEdit')) {
    if (e.target.textContent.trim() != 'Отмена') {
      document.querySelectorAll(".getEdit").forEach(butt => {
        butt.innerHTML = 'Редактировать';
      });
      e.preventDefault();
      e.target.innerHTML = 'Отмена';
      let allTr = document.querySelectorAll('tr'),
        dataUnification = e.target.dataset.unification;
      allTr.forEach(tr => {
        tr.classList.remove("openEdit");
        if (tr.classList.contains('unification' + dataUnification)) {
          tr.classList.add("openEdit")
        }
      });
    } else {
      e.preventDefault();
      document.querySelectorAll(".getEdit").forEach(butt => {
        butt.innerHTML = 'Редактировать';
      });
      let allTr = document.querySelectorAll('tr');
      allTr.forEach(tr => {
        tr.classList.remove("openEdit");
      });
    }
  }
});

const copyBtns = document.querySelectorAll(".copy");
copyBtns.forEach((copyBtn) => {
  copyBtn.addEventListener("click", () => {
    const target = copyBtn.getAttribute("data-target");
    let copyText;
    if (target.startsWith("link")) {
      const linkEl = document.querySelector(`#${target}`);
      if (linkEl === null) {
        var originalSrc = copyBtn.src;
        copyText = copyBtn.dataset.link;
        copyBtn.src = '/assets/img/green_checkmark.svg';
        setTimeout(function () {
          copyBtn.src = originalSrc;
        }, 1000);
      } else {
        copyText = linkEl.href;
      }
    } else {
      const copyTextContainer = document.querySelector(`#${target}`);
      const tempTextareaEl = document.createElement("textarea");
      tempTextareaEl.value = copyTextContainer.innerText;
      var originalText = copyTextContainer.innerText;
      document.body.appendChild(tempTextareaEl);
      tempTextareaEl.select();
      document.execCommand("copy");
      document.body.removeChild(tempTextareaEl);
      copyTextContainer.innerText = 'Скопировано';
      setTimeout(function () {
        copyTextContainer.innerText = originalText;
      }, 1000);
      return;
    }
    const tempTextareaEl = document.createElement("textarea");
    tempTextareaEl.value = copyText;
    document.body.appendChild(tempTextareaEl);
    tempTextareaEl.select();
    document.execCommand("copy");
    document.body.removeChild(tempTextareaEl);
  });
});

const statusBlocks = document.querySelectorAll('.status');
function getStatus() {
  const formData = new FormData();
  const formAction = '/status-proxy';
  const modemID = this.dataset.modem;
  const type = this.dataset.type;
  const loginKraken = this.dataset.login;
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  formData.append('modem', modemID);
  formData.append('type', type);
  formData.append('login', loginKraken);
  ajaxSend(formData, formAction)
    .then((response) => {
      var data = JSON.parse(response);
      if (data === 0) {
        this.classList.add('off');
        this.textContent = 'Offline';
      } else if (data === 1) {
        this.classList.remove('off');
        this.textContent = 'Online';
      }
    })
    .catch((err) => console.error(err))
}

statusBlocks.forEach(block => {
  block.addEventListener('click', getStatus);
});