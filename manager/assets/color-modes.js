(() => {
    const THEME = 'coreui-free-bootstrap-admin-template-theme';

    const getStoredTheme = () => localStorage.getItem(THEME);
    const setStoredTheme = theme => localStorage.setItem(THEME, theme);

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();

        if (storedTheme) {
            return storedTheme;
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    const setTheme = theme => {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-coreui-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-coreui-theme', theme);
        }

        const event = new Event('ColorSchemeChange');
        document.documentElement.dispatchEvent(event);
    }

    setTheme(getPreferredTheme())

    const showActiveTheme = theme => {
        const activeThemeIcon = document.querySelector('.theme-icon-active');
        const currentIconType = activeThemeIcon.getAttribute('data-icon-type');
        const btnToActive = document.querySelector(`[data-coreui-theme-value="${theme}"]`);
        const svgOfActiveBtn = btnToActive.querySelector('i').getAttribute('data-icon-type');

        for (const element of document.querySelectorAll('[data-coreui-theme-value]')) {
            element.classList.remove('active')
        }

        btnToActive.classList.add('active')

        activeThemeIcon.classList.remove(currentIconType);
        activeThemeIcon.classList.add(svgOfActiveBtn);
        activeThemeIcon.setAttribute('data-icon-type', svgOfActiveBtn);
    }

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme()
        if (storedTheme !== 'light' || storedTheme !== 'dark') {
            setTheme(getPreferredTheme())
        }
    })

    window.addEventListener('DOMContentLoaded', () => {
        showActiveTheme(getPreferredTheme())

        for (const toggle of document.querySelectorAll('[data-coreui-theme-value]')) {
            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-coreui-theme-value')
                setStoredTheme(theme)
                setTheme(theme)
                showActiveTheme(theme)
            })
        }
    })
})()