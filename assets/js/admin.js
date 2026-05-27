document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    const navItems = document.querySelectorAll('.nav-item');
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('#admin-sidebar');
    const sidebarBackdrop = document.querySelector('[data-sidebar-close]');
    const copyButtons = document.querySelectorAll('[data-copy-text]');

    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.2s ease';
        }, 2600);
    });

    navItems.forEach((item) => {
        item.addEventListener('pointerdown', () => {
            item.style.transform = 'scale(0.985)';
        });

        item.addEventListener('pointerup', () => {
            item.style.transform = '';
        });

        item.addEventListener('pointerleave', () => {
            item.style.transform = '';
        });
    });

    const closeSidebar = () => {
        document.body.classList.remove('sidebar-open');

        if (menuToggle) {
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', '打开导航菜单');
        }
    };

    const openSidebar = () => {
        document.body.classList.add('sidebar-open');

        if (menuToggle) {
            menuToggle.setAttribute('aria-expanded', 'true');
            menuToggle.setAttribute('aria-label', '关闭导航菜单');
        }

        const firstNavItem = sidebar ? sidebar.querySelector('.nav-item') : null;

        if (firstNavItem instanceof HTMLElement) {
            firstNavItem.focus({ preventScroll: true });
        }
    };

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            if (document.body.classList.contains('sidebar-open')) {
                closeSidebar();
                return;
            }

            openSidebar();
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeSidebar);
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    navItems.forEach((item) => {
        item.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 768px)').matches) {
                closeSidebar();
            }
        });
    });

    const showToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 1800);
    };

    copyButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const copyText = button.dataset.copyText || '';
            const originalText = button.textContent;

            if (!copyText) {
                return;
            }

            try {
                await navigator.clipboard.writeText(copyText);
                button.textContent = '已复制';
                showToast('已复制短链');
            } catch (error) {
                showToast('复制失败，请手动复制', 'error');
            }

            setTimeout(() => {
                button.textContent = originalText;
            }, 1200);
        });
    });

    const toastMessage = document.body.dataset.toast;

    if (toastMessage) {
        showToast(toastMessage);
    }
});
