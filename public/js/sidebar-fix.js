let initialLoad = true;

document.addEventListener("livewire:navigated", () => {
    if (initialLoad) {
        initialLoad = false;
        document
            .querySelectorAll(
                '[data-lte-toggle="treeview"], [data-lte-toggle="sidebar"]',
            )
            .forEach((e) => {
                e.dataset.lteBound = "true";
            });
        return;
    }

    // Re-bind Treeview toggles
    document.querySelectorAll('[data-lte-toggle="treeview"]').forEach((e) => {
        if (e.dataset.lteBound) {
            return;
        }
        e.dataset.lteBound = "true";
        e.addEventListener("click", (event) => {
            const target = event.target;
            const navItem = target.closest(".nav-item");
            const navLink = target.closest(".nav-link");
            const navTreeview = navItem?.querySelector(".nav-treeview");
            const currentToggle = event.currentTarget;

            if (navTreeview && navItem) {
                if (
                    target?.getAttribute("href") === "#" ||
                    navLink?.getAttribute("href") === "#"
                ) {
                    event.preventDefault();
                }
                const accordion = currentToggle.dataset.accordion;
                const speed = currentToggle.dataset.animationSpeed;
                const config = {
                    accordion:
                        accordion === undefined ? true : accordion === "true",
                    animationSpeed: speed === undefined ? 300 : Number(speed),
                };

                if (window.adminlte && window.adminlte.Treeview) {
                    new window.adminlte.Treeview(navItem, config).toggle();
                }
            }
        });
    });

    // Re-bind Sidebar toggles
    document.querySelectorAll('[data-lte-toggle="sidebar"]').forEach((e) => {
        if (e.dataset.lteBound) {
            return;
        }
        e.dataset.lteBound = "true";
        e.addEventListener("click", (event) => {
            event.preventDefault();
            const sidebar = document.querySelector(".app-sidebar");
            if (sidebar && window.adminlte && window.adminlte.PushMenu) {
                const breakpoint = sidebar.dataset.sidebarBreakpoint;
                const persistence = sidebar.dataset.enablePersistence;
                const config = {
                    sidebarBreakpoint:
                        breakpoint === undefined ? 992 : Number(breakpoint),
                    enablePersistence: persistence === "true",
                };
                const pushMenu = new window.adminlte.PushMenu(sidebar, config);
                pushMenu.toggle();
            }
        });
    });
});
