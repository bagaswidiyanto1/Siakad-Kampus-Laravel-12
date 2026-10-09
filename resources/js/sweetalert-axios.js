// resources/js/app-ajax.js
import axios from "axios";
import Swal from "sweetalert2";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Ambil CSRF token dari meta tag Laravel
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

// ======================================================
// KONFIGURASI TOAST (untuk notifikasi sukses ringan)
// ======================================================
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

// ======================================================
// 1. HANDLE FORM SUBMIT VIA AJAX (class="ajax-form")
// ======================================================
function initAjaxForms() {
    document
        .querySelectorAll("form.ajax-form:not([data-ajax-bound])")
        .forEach((form) => {
            form.setAttribute("data-ajax-bound", "true");

            form.addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(form);
                const method = form.dataset.method || form.method || "POST";
                const url = form.action;
                const submitBtn = form.querySelector('[type="submit"]');
                const originalBtnText = submitBtn ? submitBtn.innerHTML : null;

                // Loading state di tombol
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 mr-2 inline text-white" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Menyimpan...
                `;
                }

                // Loading overlay SweetAlert (untuk proses yang agak lama)
                Swal.fire({
                    title: "Memproses...",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                axios({
                    method:
                        method.toLowerCase() === "put" ||
                        method.toLowerCase() === "patch"
                            ? "post"
                            : method,
                    url: url,
                    data: formData,
                    headers: { "Content-Type": "multipart/form-data" },
                })
                    .then((response) => {
                        Swal.close();
                        Toast.fire({
                            icon: "success",
                            title:
                                response.data.message ||
                                "Data berhasil disimpan",
                        });

                        // Redirect kalau backend kirim redirect URL, atau reload
                        if (response.data.redirect) {
                            setTimeout(
                                () =>
                                    (window.location.href =
                                        response.data.redirect),
                                800,
                            );
                        } else if (form.dataset.reload !== "false") {
                            setTimeout(() => window.location.reload(), 800);
                        }
                    })
                    .catch((error) => {
                        Swal.close();

                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors || {};
                            const items = Object.entries(errors)
                                .map(([field, messages]) => {
                                    const label = field
                                        .replace(/_/g, " ")
                                        .replace(/\b\w/g, (c) =>
                                            c.toUpperCase(),
                                        );
                                    const msg = Array.isArray(messages)
                                        ? messages[0]
                                        : messages;
                                    return `
                                            <li class="flex items-start gap-2 text-left">
                                                <svg class="w-5 h-5 flex-shrink-0 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                                </svg>
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-red-600">${label}</span>
                                                    <span class="text-sm text-gray-700">${msg}</span>
                                                </div>
                                            </li>
                                        `;
                                })
                                .join("");

                            Swal.fire({
                                icon: "error",
                                title: "Validasi Gagal",
                                html: `
                                        <ul class="mt-2 space-y-2 max-h-72 overflow-y-auto pr-1">
                                            ${items}
                                        </ul>
                                    `,
                                confirmButtonText: "OK",
                                confirmButtonColor: "#7c3aed",
                                width: 480,
                                customClass: {
                                    popup: "rounded-2xl",
                                    title: "text-gray-900",
                                },
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text:
                                    error.response?.data?.message ||
                                    "Terjadi kesalahan, coba lagi.",
                            });
                        }
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                        }
                    });
            });
        });
}

// ======================================================
// 2. HANDLE TOMBOL HAPUS VIA AJAX (class="btn-delete-ajax")
// ======================================================
function initDeleteButtons() {
    document
        .querySelectorAll(".btn-delete-ajax:not([data-ajax-bound])")
        .forEach((btn) => {
            btn.setAttribute("data-ajax-bound", "true");

            btn.addEventListener("click", function (e) {
                e.preventDefault();

                const url = btn.dataset.url || btn.getAttribute("href");
                const itemName = btn.dataset.name || "data ini";

                Swal.fire({
                    title: "Yakin hapus?",
                    text: `${itemName} akan dihapus permanen.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#7c3aed", // purple, sesuai tema kamu
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: "Ya, hapus",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    Swal.fire({
                        title: "Menghapus...",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                    });

                    axios
                        .delete(url)
                        .then((response) => {
                            Swal.close();
                            Toast.fire({
                                icon: "success",
                                title:
                                    response.data.message ||
                                    "Data berhasil dihapus",
                            });

                            // Kalau dipakai di baris tabel DataTables, reload DataTable-nya
                            if (
                                window.$ &&
                                $.fn.DataTable &&
                                $.fn.DataTable.isDataTable
                            ) {
                                const table = btn.closest("table");
                                if (
                                    table &&
                                    $.fn.DataTable.isDataTable(table)
                                ) {
                                    $(table)
                                        .DataTable()
                                        .ajax.reload(null, false);
                                    return;
                                }
                            }

                            setTimeout(() => window.location.reload(), 800);
                        })
                        .catch((error) => {
                            Swal.close();
                            Swal.fire({
                                icon: "error",
                                title: "Gagal Menghapus",
                                text:
                                    error.response?.data?.message ||
                                    "Terjadi kesalahan, coba lagi.",
                            });
                        });
                });
            });
        });
}

// ======================================================
// INITIALIZE
// ======================================================
function initAjaxPlugins() {
    initAjaxForms();
    initDeleteButtons();
}

document.addEventListener("DOMContentLoaded", initAjaxPlugins);

// Expose global supaya bisa dipanggil ulang manual (modal, partial reload, dsb)
window.initAjaxPlugins = initAjaxPlugins;
window.Swal = Swal;
