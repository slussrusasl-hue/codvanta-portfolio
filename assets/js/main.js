document.addEventListener("DOMContentLoaded", function () {

    const navbar = document.getElementById("mainNav");

    function updateNavbar() {
        if (window.scrollY > 30) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    }

    updateNavbar();
    window.addEventListener("scroll", updateNavbar);

    // Highlight the navigation item for the visible section.
    const sections = document.querySelectorAll("main section[id]");
    const navLinks = document.querySelectorAll(".nav-link");

    const observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    navLinks.forEach(function (link) {
                        link.classList.remove("active");

                        if (link.getAttribute("href") === "#" + entry.target.id) {
                            link.classList.add("active");
                        }
                    });
                }
            });
        },
        {
            rootMargin: "-35% 0px -55% 0px"
        }
    );

    sections.forEach(function (section) {
        observer.observe(section);
    });

    // Close mobile navbar after clicking a navigation link.
    const navbarCollapse = document.getElementById("navbarContent");

    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            if (navbarCollapse.classList.contains("show")) {
                const collapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (collapse) {
                    collapse.hide();
                }
            }
        });
    });

});
