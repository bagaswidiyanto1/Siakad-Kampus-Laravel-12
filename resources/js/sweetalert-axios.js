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
                            // Validation error Laravel
                            const errors = error.response.data.errors;
                            const messages = Object.values(errors)
                                .flat()
                                .join("<br>");
                            Swal.fire({
                                icon: "error",
                                title: "Validasi Gagal",
                                html: messages,
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
