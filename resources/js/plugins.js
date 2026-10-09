import AutoNumeric from "autonumeric";
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

// ======================================================
// AutoNumeric
// ======================================================

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-number]").forEach((element) => {
        new AutoNumeric(element, {
            digitGroupSeparator: ",",
            decimalCharacter: ".",
            decimalPlaces: 0,
            minimumValue: "0",
            unformatOnSubmit: true,
        });
    });
});
