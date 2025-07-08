document.addEventListener("DOMContentLoaded", () => {
    // Toggle mobile menu
    const mobileMenuButton = document.getElementById("mobile-menu-button")
    const mobileMenu = document.getElementById("mobile-menu")

    if (mobileMenuButton && mobileMenu) {
      mobileMenuButton.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden")
      })
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        e.preventDefault()

        const targetId = this.getAttribute("href")
        if (targetId === "#") return

        const targetElement = document.querySelector(targetId)

        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: "smooth",
          })
        }
      })
    })

    // Dark mode toggle
    const darkModeToggle = document.getElementById("dark-mode-toggle")

    if (darkModeToggle) {
      // Always default to light mode
      document.documentElement.classList.remove("dark")
      localStorage.setItem("theme", "light")
      darkModeToggle.checked = false

      // Check only for saved theme preference (ignore system preference)
      const savedTheme = localStorage.getItem("theme")

      if (savedTheme === "dark") {
        document.documentElement.classList.add("dark")
        darkModeToggle.checked = true
      }

      // Add event listener to toggle
      darkModeToggle.addEventListener("change", function () {
        if (this.checked) {
          document.documentElement.classList.add("dark")
          localStorage.setItem("theme", "dark")
        } else {
          document.documentElement.classList.remove("dark")
          localStorage.setItem("theme", "light")
        }
      })
    }

    // Initialize any tooltips
    const tooltips = document.querySelectorAll("[data-tooltip]")

    tooltips.forEach((tooltip) => {
      tooltip.addEventListener("mouseenter", function () {
        const tooltipText = this.getAttribute("data-tooltip")

        const tooltipElement = document.createElement("div")
        tooltipElement.className =
          "absolute z-50 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded shadow-lg dark:bg-gray-700"
        tooltipElement.textContent = tooltipText
        tooltipElement.style.bottom = "calc(100% + 5px)"
        tooltipElement.style.left = "50%"
        tooltipElement.style.transform = "translateX(-50%)"

        this.style.position = "relative"
        this.appendChild(tooltipElement)
      })

      tooltip.addEventListener("mouseleave", function () {
        const tooltipElement = this.querySelector("div")
        if (tooltipElement) {
          tooltipElement.remove()
        }
      })
    })
  })
