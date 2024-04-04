var buttons = document.querySelectorAll('.btn.nomail');
var modal = document.querySelector('.modal.nomail');
var closeModal = document.querySelector('.closeModal');
var overlay = document.querySelector('.overlay');
const currentUrl = window.location.href;
buttons.forEach(function (button) {
  button.addEventListener('click', function () {
    modal.classList.add('active');
    overlay.classList.add('active');
  });
});
document.getElementById('emailForm').addEventListener('submit', function (event) {
  event.preventDefault();
  const formData = new FormData();
  var emailInput = document.getElementById('email');
  var telegramInput = document.getElementById('telegram');
  if (emailInput) {
    formData.append('email', emailInput.value);
  }
  if (telegramInput) {
    formData.append('telegram_chat_id', telegramInput.value);
  }
  const formAction = '/fetch/save/email';
  ajaxSend(formData, formAction)
    .then((response) => {
      var data = JSON.parse(response);
      if (data.error) {
        if (currentUrl === dom) {
          var errorMessage = document.createElement('div');
        } else if (currentUrl === dom + "buy-proxy") {
          var errorMessage = document.querySelector('.nomail .massage');
        }
        errorMessage.textContent = 'Пользователь с таким email уже существует';
        if (currentUrl === dom) {
          emailInput.parentNode.appendChild(errorMessage);
        }
      } else {
        if (currentUrl === dom) {
          window.location.href = '/';
        } else if (currentUrl === dom + "buy-proxy") {
          window.location.href = '/buy-proxy';
        }
      }
    })
    .catch((err) => console.error(err))
});
let arrowDetails = document.querySelector('.answers__summary');
if (arrowDetails) {
  arrowDetails.addEventListener('click', () => {
    arrowDetails.classList.toggle('rotateArrow');
  })
}
let burger = document.querySelector('.menu-btn');
burger.addEventListener('click', () => {
  burger.classList.toggle('open');
})
window.addEventListener('click', function (event) {
  if (event.target.classList.contains("proxy__add") || event.target.classList.contains(
    "proxy__decrease")) {
    const input = event.target.closest('.proxy__application-count').querySelector(
      '.proxy__application-quantity');
    let quantity = parseInt(input.value);
    if (event.target.classList.contains("proxy__add")) {
      quantity++;
    }
    if (event.target.classList.contains("proxy__decrease") && quantity > 1) {
      quantity--;
    }
    input.value = quantity;
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const btnSubmenus = document.querySelectorAll(".btn-submenu");
  const typeProxySelect = document.querySelector(".type__proxy");
  btnSubmenus.forEach(function (btnSubmenu) {
    btnSubmenu.addEventListener("click", function (event) {
      event.stopPropagation();
      const submenu = this.nextElementSibling;
      if (submenu) {
        submenu.classList.toggle("active");
      }
    });
  });

  document.addEventListener("click", function (event) {
    btnSubmenus.forEach(function (btnSubmenu) {
      const submenu = btnSubmenu.nextElementSibling;
      if (submenu && submenu.classList.contains("active")) {
        submenu.classList.remove("active");
      }
    });
  });

  document.querySelector("body").addEventListener("click", function (event) {
    if (event.target.classList.contains("submenu")) {
      event.stopPropagation();
    }
  });

  document.addEventListener("click", function (event) {
    if (event.target.tagName === "LI" && event.target.closest(".submenu")) {
      const dataGeneral = event.target.getAttribute("data-general");
      const dataPrivate = event.target.getAttribute("data-private");
      const country = event.target.getAttribute("data-country");
      const selectedType = typeProxySelect.value;
      const submenu = event.target.closest(".submenu");
      if (submenu) {
        submenu.setAttribute("data-general", dataGeneral);
        submenu.setAttribute("data-private", dataPrivate);
        const dataId = submenu.getAttribute("data-id");
        const tariffBlock = document.querySelector(
          `.tariff__block[data-tariff="${dataId}"]`);
        if (tariffBlock) {
          const countryInpup = tariffBlock.querySelector(".countryInpup");
          const costElement = tariffBlock.querySelector(".cost");
          countryInpup.value = country;
          if (costElement) {
            if (selectedType === "general") {
              costElement.textContent = dataGeneral;
            } else if (selectedType === "private") {
              costElement.textContent = dataPrivate;
            }
          }
        }
      }
    }
  });

  if (typeProxySelect) {
    typeProxySelect.addEventListener("change", function () {
      const selectedType = this.value;
      var activeSubmenu = document.querySelector(".submenu");
      if (activeSubmenu) {
        const dataGeneral = activeSubmenu.getAttribute("data-general");
        const dataPrivate = activeSubmenu.getAttribute("data-private");
        const dataId = activeSubmenu.getAttribute("data-id");
        const tariffBlock = document.querySelector(
          `.tariff__block[data-tariff="${dataId}"]`);
        if (tariffBlock) {
          const costElement = tariffBlock.querySelector(".cost");
          if (costElement) {
            if (selectedType === "general") {
              costElement.textContent = dataGeneral;
            } else if (selectedType === "private") {
              costElement.textContent = dataPrivate;
            }
          }
        }
      }
    });
  }
});

var discountP = 0;
let timeoutId;
let initialCostValue;

function updateInitialCostValue(tariffBlock) {
  const costElement = tariffBlock.querySelector('.cost');
  initialCostValue = parseFloat(costElement.textContent);
}

function checkPromocode(amount, quantity, promocodeValue, promocodeInput) {
  const formAction = "/fetch/promocode";
  const formData = new FormData();
  formData.append('promocode', promocodeValue);
  formData.append('quantity', amount);
  formData.append('rent', quantity);
  const tariffID = promocodeInput.getAttribute("data-id");
  const tariffBlock = document.querySelector(`.tariff__block[data-tariff="${tariffID}"]`);
  if (initialCostValue === undefined) {
    updateInitialCostValue(tariffBlock);
  }
  ajaxSend(formData, formAction)
    .then((response) => {
      var data = JSON.parse(response);
      if (data.exists) {
        promocodeInput.classList.add('true');
        promocodeInput.classList.remove('false');
        if (tariffBlock) {
          tariffBlock.classList.add('true');
          const costElement = tariffBlock.querySelector('.cost_tariff');
          const costDayElement = tariffBlock.querySelector('.cost_day');
          if (costDayElement) {
            discountP = data.discount;
          }
        }
      } else {
        promocodeInput.classList.add('false');
        promocodeInput.classList.remove('true');
        discountP = 0;
      }
    })
    .catch((err) => console.error(err))
}

document.addEventListener('change', function (event) {
  const target = event.target;
  if (target.classList.contains('type__proxy')) {
    const tariffBlock = target.closest('.tariff__block');
    updateInitialCostValue(tariffBlock);
  }
});

const observer = new MutationObserver(function (mutations) {
  mutations.forEach(function (mutation) {
    if (mutation.type === 'attributes') {
      const tariffBlock = mutation.target.closest('.tariff__block');
      updateInitialCostValue(tariffBlock);
    }
  });
});

const submenuElements = document.querySelectorAll('.submenu');
submenuElements.forEach(function (submenuElement) {
  observer.observe(submenuElement, {
    attributes: true
  });
});

const tariffBlocks = document.querySelectorAll('.tariff__block');
function tariffsi(country, type) {
  const tariffPrices = [];
  tariffBlocks.forEach((tariffBlock) => {
    const dataTariff = tariffBlock.getAttribute('data-tariff');
    const tariffInfo = costd[dataTariff];
    if (tariffInfo) {
      const countryValue = country;
      const periodValue = tariffInfo.period;
      const properties = tariffInfo.properties;
      const generalPrice = tariffInfo.general_price[0];
      const privatePrice = tariffInfo.private_price[0];
      price = type === 'general' ? generalPrice : privatePrice;
      tariffPrices.push({
        dataTariff: dataTariff,
        price: price
      });
    }
  });
  return tariffPrices;
}

tariffBlocks.forEach((tariffBlock) => {
  const typeProxyElement = tariffBlock.querySelector('.type__proxy__day');
  const countryDayElement = tariffBlock.querySelector('.country_day');
  const amountDaysElement = tariffBlock.querySelector('.amount_days');
  const quantityElement = tariffBlock.querySelector('.q_d');
  const proxy__add = tariffBlock.querySelector('.proxy__add');
  const proxy__decrease = tariffBlock.querySelector('.proxy__decrease');
  const costElement = tariffBlock.querySelector('.cost_day');
  const dayElement = tariffBlock.querySelector('.days');
  const sales__block = tariffBlock.querySelector('.sales__block');
  const promocodeInput = tariffBlock.querySelector('.promocode');
  function getValue(element) {
    if (element.tagName.toLowerCase() === 'input') {
      return element.value;
    } else if (element.tagName.toLowerCase() === 'select') {
      return element.options[element.selectedIndex].value;
    } else {
      return 'Неподдерживаемый тип элемента';
    }
  }

  function allDiscount(country, type, amount, quantity) {
    const formAction = "/fetch/discount";
    const formData = new FormData();
    formData.append('country', country);
    formData.append('type', type);
    formData.append('month', amount);
    formData.append('count', quantity);
    var data;
    const disc = ajaxSend(formData, formAction)
      .then((response) => {
        try {
          data = JSON.parse(response);
          return data;
        } catch (e) {
          console.error('Ошибка при парсинге JSON:', e);
        }
      })
      .catch((err) => console.error('Ошибка при выполнении запроса:', err))
    return disc;
  }

  function updateAndFindPrice() {
    const typeValue = getValue(typeProxyElement);
    const countryValue = getValue(countryDayElement);
    const amountValue = getValue(amountDaysElement);
    const quantityValue = getValue(quantityElement);
    var inputValue = promocodeInput.value;
    if (inputValue.trim() !== "") {
      checkPromocode(amountValue, quantityValue, inputValue, promocodeInput);
    }
    var discountValue = discountP;
    var amountDaysDiscount = 0;
    var amountProxyDiscount = 0;
    var amountPairsProxyDiscount = 0;
    if (isNaN(amountValue) || isNaN(quantityValue)) {
      console.error('Некорректные данные для amount или quantity');
      return;
    }
    allDiscount(countryValue, typeValue, amountValue, quantityValue)
      .then((result) => {
        if (result.amountDaysDiscount) {
          amountDaysDiscount = result.amountDaysDiscount;
        }
        if (result.amountProxyDiscount) {
          amountProxyDiscount = result.amountProxyDiscount;
        }
        if (result.amountPairsProxyDiscount) {
          amountPairsProxyDiscount = result.amountPairsProxyDiscount;
        }

        var discounts = [{
          discountValue: discountValue,
          promocode_discount: promocode_discount
        },
        {
          amountDaysDiscount: amountDaysDiscount,
          days_discount: days_discount
        },
        {
          amountProxyDiscount: amountProxyDiscount,
          proxy_discount: proxy_discount
        },
        {
          amountPairsProxyDiscount: amountPairsProxyDiscount,
          proxy_pairs_discount: proxy_pairs_discount
        }
        ];

        findPrice(countryValue, typeValue, amountValue, quantityValue, discounts);
      })
      .catch((error) => {
        console.error(error);
      });
  }

  if (typeProxyElement) {
    typeProxyElement.addEventListener('change', updateAndFindPrice);
    countryDayElement.addEventListener('change', updateAndFindPrice);
    amountDaysElement.addEventListener('change', updateAndFindPrice);
    proxy__add.addEventListener('click', function () {
      setTimeout(function () {
        updateAndFindPrice();
      }, 0);
    });
    proxy__decrease.addEventListener('click', function () {
      setTimeout(function () {
        updateAndFindPrice();
      }, 0);
    });
    promocodeInput.addEventListener('input', function () {
      clearTimeout(timeoutId);
    });
    promocodeInput.addEventListener('blur', function () {
      timeoutId = setTimeout(updateAndFindPrice, 100);
      timeoutId = setTimeout(updateAndFindPrice, 800);
    });

  }

  function findPrice(country, type, amount, quantity, discounts) {
    var sum;
    let sales = ``;
    if (tariffs) {
      const t = tariffsi(country, type);
      const dataTariff = tariffBlock.getAttribute('data-tariff');
      let lastS = false;
      var lastCost;
      t.forEach(element => {
        if (element.dataTariff === dataTariff) {
          var price = element.price;
          sum = price * quantity;
          lastCost = price * quantity;
        }
      });

      if (discounts[0].discountValue) {
        if (promocode_discount) {
          discounts.forEach(discount => {
            const keys = Object.keys(discount);
            const secondValueKey = keys[1];
            if (secondValueKey && discount[secondValueKey]) {
              const firstValue = discount[keys[0]];
              sum -= sum * (firstValue / 100);
              if (secondValueKey == 'promocode_discount' && firstValue > 0) {
                sales += `<span>Скидка по промокоду ${firstValue}%</span>`;
                lastS = true;
              }
              if (secondValueKey == 'days_discount' && firstValue > 0) {
                sales += `<span>Скидка по сроку ${firstValue}%</span>`;
                lastS = true;
              }
              if (secondValueKey == 'proxy_discount' && firstValue > 0) {
                sales += `<span>Скидка по количеству ${firstValue}%</span>`;
                lastS = true;
              }
              if (secondValueKey == 'proxy_pairs_discount' && firstValue > 0) {
                sales += `<span>Скидка ${firstValue}%</span>`;
                lastS = true;
              }
            }
          });
          costElement.textContent = sum.toFixed(2);
        } else {
          sum -= sum * (discounts[0].discountValue / 100);
          if (secondValueKey == 'promocode_discount' && firstValue > 0) {
            sales += `<span>Скидка по промокоду ${firstValue}%</span>`;
            lastS = true;
          }
          costElement.textContent = sum.toFixed(2);
        }
      } else {
        discounts.forEach(discount => {
          const keys = Object.keys(discount);
          const secondValueKey = keys[1];

          if (secondValueKey && discount[secondValueKey]) {
            const firstValue = discount[keys[0]];
            sum -= sum * (firstValue / 100);

            if (secondValueKey == 'promocode_discount' && firstValue > 0) {
              sales += `<span>Скидка по промокоду ${firstValue}%</span>`;
              lastS = true;
            }
            if (secondValueKey == 'days_discount' && firstValue > 0) {
              sales += `<span>Скидка по сроку ${firstValue}%</span>`;
              lastS = true;
            }
            if (secondValueKey == 'proxy_discount' && firstValue > 0) {
              sales += `<span>Скидка по количеству ${firstValue}%</span>`;
              lastS = true;
            }
            if (secondValueKey == 'proxy_pairs_discount' && firstValue > 0) {
              sales += `<span>Скидка ${firstValue}%</span>`;
              lastS = true;
            }
          }
          costElement.textContent = sum.toFixed(2);
        });
      }
      dayElement.textContent = amount;
      sales__block.innerHTML = sales;
      if (lastS) {
        costElement.setAttribute('data-last', lastCost + '.00 $')
      }
    } else {
      const countryInfo = costd.find(item => item.country === country);
      if (countryInfo) {
        const price = type === 'general' ? countryInfo.general_price : countryInfo.private_price;
        sum = (price * amount) * quantity;
        let lastS = false;
        const lastCost = (price * amount) * quantity;
        costElement.textContent = sum;
        if (discounts[0].discountValue) {
          if (promocode_discount) {
            discounts.forEach(discount => {
              const keys = Object.keys(discount);
              const secondValueKey = keys[1];

              if (secondValueKey && discount[secondValueKey]) {
                const firstValue = discount[keys[0]];
                sum -= sum * (firstValue / 100);
                if (secondValueKey == 'promocode_discount' && firstValue > 0) {
                  sales += `<span>Скидка по промокоду ${firstValue}%</span>`;
                  lastS = true;
                }
                if (secondValueKey == 'days_discount' && firstValue > 0) {
                  sales += `<span>Скидка по сроку ${firstValue}%</span>`;
                  lastS = true;
                }
                if (secondValueKey == 'proxy_discount' && firstValue > 0) {
                  sales += `<span>Скидка по количеству ${firstValue}%</span>`;
                  lastS = true;
                }
                if (secondValueKey == 'proxy_pairs_discount' && firstValue > 0) {
                  sales += `<span>Скидка ${firstValue}%</span>`;
                  lastS = true;
                }
              }
            });
            costElement.textContent = sum.toFixed(2);
          } else {
            sum -= sum * (discounts[0].discountValue / 100);
            if (secondValueKey == 'promocode_discount' && firstValue > 0) {
              sales += `<span>Скидка по промокоду ${firstValue}%</span>`;
              lastS = true;
            }
            costElement.textContent = sum.toFixed(2);
          }
        } else {
          discounts.forEach(discount => {
            const keys = Object.keys(discount);
            const secondValueKey = keys[1];

            if (secondValueKey && discount[secondValueKey]) {
              const firstValue = discount[keys[0]];
              sum -= sum * (firstValue / 100);
              if (secondValueKey == 'promocode_discount' && firstValue > 0) {
                sales += `<span>Скидка по промокоду ${firstValue}%</span>`;
                lastS = true;
              }
              if (secondValueKey == 'days_discount' && firstValue > 0) {
                sales += `<span>Скидка по сроку ${firstValue}%</span>`;
                lastS = true;
              }
              if (secondValueKey == 'proxy_discount' && firstValue > 0) {
                sales += `<span>Скидка по количеству ${firstValue}%</span>`;
                lastS = true;
              }
              if (secondValueKey == 'proxy_pairs_discount' && firstValue > 0) {
                sales += `<span>Скидка ${firstValue}%</span>`;
                lastS = true;
              }
            }
            costElement.textContent = sum.toFixed(2);
          });
        }
        sales__block.innerHTML = sales;
        dayElement.textContent = amount;
        if (lastS) {
          costElement.setAttribute('data-last', lastCost + '.00 $')
        }
      } else {
        return 'Страна не найдена в массиве costd';
      }
    }
  }

  if (typeProxyElement) {
    updateAndFindPrice();
  }
});