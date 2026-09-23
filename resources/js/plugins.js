import TomSelect from "tom-select";
import "tom-select/dist/css/tom-select.css";

// ======================================================
// TOM SELECT
// ======================================================

function initTomSelect() {
    document.querySelectorAll("[data-tom-select]").forEach((element) => {
        if (element.tomselect) {
            return;
        }

        const instance = new TomSelect(element, {
            create: false,
            dropdownDirection: "up",
            dropdownParent: "body",
        });

        // Tambah/hapus class saat dropdown dibuka-tutup, untuk trigger rotate arrow
        instance.on("dropdown_open", () => {
            instance.wrapper.classList.add("ts-dropdown-active");
        });
        instance.on("dropdown_close", () => {
            instance.wrapper.classList.remove("ts-dropdown-active");
        });
    });
}

// ======================================================
// INITIALIZE
// ======================================================

function initPlugins() {
    initTomSelect();
}

document.addEventListener("DOMContentLoaded", initPlugins);
