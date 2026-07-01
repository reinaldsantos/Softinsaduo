const temaSelect = document.querySelector('select[name="tema"]');

if (temaSelect) {
    const temaGuardado = localStorage.getItem("tema");

    if (temaGuardado) {
        temaSelect.value = temaGuardado;
        document.body.classList.toggle("dark-mode", temaGuardado === "escuro");
    }

    temaSelect.addEventListener("change", () => {
        const tema = temaSelect.value;

        localStorage.setItem("tema", tema);

        document.body.classList.toggle("dark-mode", tema === "escuro");
    });
}