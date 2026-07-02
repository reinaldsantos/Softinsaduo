document.addEventListener("DOMContentLoaded", () => {
    const temaGuardado = localStorage.getItem("tema") || "claro";

    document.documentElement.classList.toggle("dark-mode", temaGuardado === "escuro");
    document.body.classList.toggle("dark-mode", temaGuardado === "escuro");

    const temaSelect = document.querySelector('select[name="tema"]');

    if (temaSelect) {
        temaSelect.value = temaGuardado;

        temaSelect.addEventListener("change", () => {
            const tema = temaSelect.value;

            localStorage.setItem("tema", tema);

            document.documentElement.classList.toggle("dark-mode", tema === "escuro");
            document.body.classList.toggle("dark-mode", tema === "escuro");
        });
    }
});