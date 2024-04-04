class ItcTabs {
    constructor(target, config, key) {
        const defaultConfig = {};
        this._key = key;
        this._config = Object.assign(defaultConfig, config);
        this._elTabs = typeof target === 'string' ? document.querySelector(target) : target;
        this._elButtons = this._elTabs.querySelectorAll('.tabs__btn');
        this._elPanes = this._elTabs.querySelectorAll('.tabs__pane');
        this._eventShow = new Event('tab.itc.change');
        this._init();
        this._events();
    }
    _init() {
        const activeTabIndex = localStorage.getItem(this._key);
        this._elTabs.setAttribute('role', 'tablist');
        this._elButtons.forEach((el, index) => {
            el.dataset.index = index;
            el.setAttribute('role', 'tab');
            this._elPanes[index].setAttribute('role', 'tabpanel');
        });
        
        // Check localStorage for activeTabIndex and show that tab or the first one by default.
        // const activeTabIndex = localStorage.getItem('activeTabIndex');
        this.showByIndex(activeTabIndex || 0);
    }
    show(elLinkTarget) {
        const elPaneTarget = this._elPanes[elLinkTarget.dataset.index];
        const elLinkActive = this._elTabs.querySelector('.tabs__btn_active');
        const elPaneShow = this._elTabs.querySelector('.tabs__pane_show');
        if (elLinkTarget === elLinkActive) {
            return;
        }
        elLinkActive ? elLinkActive.classList.remove('tabs__btn_active') : null;
        elPaneShow ? elPaneShow.classList.remove('tabs__pane_show') : null;
        elLinkTarget.classList.add('tabs__btn_active');
        elPaneTarget.classList.add('tabs__pane_show');
        this._elTabs.dispatchEvent(this._eventShow);
        elLinkTarget.focus();

        // Save the active tab index in localStorage.
        // localStorage.setItem('activeTabIndex', elLinkTarget.dataset.index);
        localStorage.setItem(this._key, elLinkTarget.dataset.index);
    }
    showByIndex(index) {
        const elLinkTarget = this._elButtons[index];
        elLinkTarget ? this.show(elLinkTarget) : null;
    };
    _events() {
        this._elTabs.addEventListener('click', (e) => {
            const target = e.target.closest('.tabs__btn');
            if (target) {
                e.preventDefault();
                this.show(target);
            }
        });
    }
}

// инициализация .tabs как табов
new ItcTabs('.tabs', {}, 'first-tabs');
