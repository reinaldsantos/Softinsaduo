const pesquisa = document.querySelector(".search-box input");
const tarefas = document.querySelectorAll(".task-item");

if (pesquisa && tarefas.length > 0) {
    pesquisa.addEventListener("input", () => {
        const texto = pesquisa.value.toLowerCase();

        tarefas.forEach((tarefa) => {
            const conteudo = tarefa.innerText.toLowerCase();

            if (conteudo.includes(texto)) {
                tarefa.style.display = "flex";
            } else {
                tarefa.style.display = "none";
            }
        });
    });
}