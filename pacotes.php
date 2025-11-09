<?php
session_start();
if (isset($_SESSION['nivel_admin']) && $_SESSION['nivel_admin'] == 0) {
    header("Location: ./clientes.php");
    exit();
}
require_once("menu.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Pacotes - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<style>
body { background-color: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
main { padding: 2rem; margin-top: 3rem; }
.content-container { background-color: #1e293b; border-radius: 12px; padding: 2rem; }
.package-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.package-name { cursor: pointer; }
.edit-input { display: none; padding: 5px; border-radius: 4px; border: 1px solid #475569; background-color: #1e293b; color: #f8fafc; }
.edit-buttons { display: none; gap: 5px; }
.btn-edit, .btn-cancel { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; cursor: pointer; }
.btn-edit { background-color: #6366f1; border: none; color: white; }
.btn-edit:hover { background-color: #4f46e5; }
.btn-cancel { background-color: #6b7280; border: none; color: white; }
.btn-cancel:hover { background-color: #475569; }
.sortable-list { display: flex; flex-direction: column; gap: 5px; margin-top: 10px; }
.category-item { display: flex; align-items: center; justify-content: space-between; background-color: #1e293b; border: 1px solid #475569; padding: 5px 10px; border-radius: 4px; cursor: grab; }
.category-item:hover { background-color: #334155; }
.category-item input { margin-right: 8px; }
.package-container { margin-bottom: 20px; border-bottom: 1px solid #475569; padding-bottom: 10px; }

@media (max-width: 768px) {
    main { padding: 1rem; margin-top: 1rem; }
    .page-header { flex-direction: column; align-items: stretch; gap: 1rem; }
    .page-header .form-select, .page-header .btn { width: 100%; }
    .content-container { padding: 1rem; }
    .package-title { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    .edit-input, .edit-buttons { width: 100%; }
}
</style>
</head>
<body>
<main>
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h1>Gerenciar Pacotes</h1>
    <div class="d-flex align-items-center flex-wrap">
        <select id="pacote_a_excluir" class="form-select me-2 mb-2 mb-md-0">
            <option value="" selected>Selecione um pacote para excluir</option>
        </select>
        <button class="btn btn-danger me-2 mb-2 mb-md-0" onclick="confirmarExclusao()"><i class="fas fa-trash"></i> Excluir</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoPacote"><i class="fas fa-plus"></i> Novo Pacote</button>
    </div>
</div>

<div class="content-container"></div>
</main>

<!-- Modal Novo Pacote -->
<div class="modal fade" id="modalNovoPacote" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form id="formNovoPacote">
<div class="modal-header">
<h5 class="modal-title">Criar Novo Pacote</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
<label for="nome_pacote" class="form-label">Nome do Pacote</label>
<input type="text" class="form-control" id="nome_pacote" name="nome_pacote" placeholder="Digite o nome do pacote" required>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
<button type="submit" class="btn btn-primary">Salvar</button>
</div>
</form>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Carregar pacotes e categorias
function carregarPacotes() {
    fetch("./api/pacotes.php", { method: "POST", body: new URLSearchParams({ listar_pacotes: 1 }) })
    .then(res => res.json())
    .then(data => {
        const container = document.querySelector(".content-container");
        const selectExcluir = document.getElementById("pacote_a_excluir");
        container.innerHTML = "";
        selectExcluir.innerHTML = '<option value="" selected>Selecione um pacote para excluir</option>';

        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = `<div class="text-center p-4"><h4>Nenhum pacote criado ainda.</h4><p>Clique em "+ Novo Pacote" para começar.</p></div>`;
            return;
        }

        data.forEach(pacote => {
            // Pacote container
            const pacoteDiv = document.createElement("div");
            pacoteDiv.classList.add("package-container");
            pacoteDiv.dataset.packageId = pacote.id;

            // Nome e edição
            const nomeDiv = document.createElement("div");
            nomeDiv.classList.add("package-title");
            nomeDiv.innerHTML = `
                <h5 class="package-name" onclick="editarNomePacote(${pacote.id})">${pacote.pacote} <i class="fas fa-edit ms-2" style="font-size:0.8em; color:#94a3b8"></i></h5>
                <input type="text" class="edit-input" value="${pacote.pacote}" onkeydown="handleKeyPress(event, ${pacote.id})">
                <div class="edit-buttons">
                    <button class="btn-edit" onclick="salvarNomePacote(${pacote.id})">Salvar</button>
                    <button class="btn-cancel" onclick="cancelarEdicao(${pacote.id})">Cancelar</button>
                </div>
            `;
            pacoteDiv.appendChild(nomeDiv);

            // Categorias do pacote
            const form = document.createElement("form");
            form.id = "form-pacote-" + pacote.id;
            const lista = document.createElement("div");
            lista.classList.add("sortable-list");
            lista.id = "sortable-" + pacote.id;

            // Aqui vamos buscar as categorias associadas ao pacote
            fetch("./api/categorias.php", { method: "POST", body: new URLSearchParams({ pacote_id: pacote.id }) })
            .then(res => res.json())
            .then(categorias => {
                categorias.forEach(cat => {
                    const item = document.createElement("div");
                    item.classList.add("category-item", "sortable-item");
                    item.dataset.categoryId = cat.id;
                    item.innerHTML = `<label><input type="checkbox" name="categorias[]" value="${cat.id}" ${cat.checked ? "checked" : ""}> ${cat.nome}</label>`;
                    lista.appendChild(item);
                });

                // Inicializa drag-and-drop
                new Sortable(lista, { animation: 150 });
            });

            form.appendChild(lista);
            form.innerHTML += `<button type="button" class="btn btn-success mt-2" onclick="salvarCategorias(${pacote.id})">Salvar Categorias</button>`;
            pacoteDiv.appendChild(form);

            container.appendChild(pacoteDiv);

            // Select exclusão
            const option = document.createElement("option");
            option.value = pacote.id;
            option.textContent = pacote.pacote;
            selectExcluir.appendChild(option);
        });
    });
}

// Funções de edição nome
function editarNomePacote(id) {
    const div = document.querySelector(`[data-package-id="${id}"]`);
    div.querySelector(".package-name").style.display = "none";
    div.querySelector(".edit-input").style.display = "inline-block";
    div.querySelector(".edit-buttons").style.display = "flex";
    div.querySelector(".edit-input").focus();
}
function cancelarEdicao(id) {
    const div = document.querySelector(`[data-package-id="${id}"]`);
    const input = div.querySelector(".edit-input");
    const name = div.querySelector(".package-name");
    const buttons = div.querySelector(".edit-buttons");
    input.value = name.textContent.trim();
    input.style.display = "none";
    buttons.style.display = "none";
    name.style.display = "block";
}
function handleKeyPress(e, id) {
    if(e.key === "Enter") salvarNomePacote(id);
    if(e.key === "Escape") cancelarEdicao(id);
}
function salvarNomePacote(id) {
    const div = document.querySelector(`[data-package-id="${id}"]`);
    const input = div.querySelector(".edit-input");
    const name = div.querySelector(".package-name");
    const buttons = div.querySelector(".edit-buttons");
    const novoNome = input.value.trim();
    if(!novoNome) return Swal.fire("Erro","Nome não pode ser vazio","error");

    const formData = new FormData();
    formData.append("editar_pacote_action", "1");
    formData.append("pacote_id", id);
    formData.append("novo_nome", novoNome);

    fetch("./api/pacotes.php",{method:"POST",body:formData})
    .then(res=>res.json())
    .then(data=>{
        if(data.icon==="success"){
            name.innerHTML=`${novoNome} <i class="fas fa-edit ms-2" style="font-size:0.8em;color:#94a3b8"></i>`;
            input.style.display="none";
            buttons.style.display="none";
            name.style.display="block";
        }
        Swal.fire(data.title,data.msg,data.icon);
    });
}

// Salvar categorias
function salvarCategorias(pacoteId){
    const form = document.getElementById("form-pacote-"+pacoteId);
    const checkboxes = form.querySelectorAll("input[name='categorias[]']:checked");
    const ids = Array.from(checkboxes).map(c=>c.value);
    const sortableList = form.querySelector(".sortable-list");
    const ordem = Array.from(sortableList.querySelectorAll(".sortable-item")).map(item=>item.dataset.categoryId);

    const formData = new FormData();
    formData.append("salvar_categorias_pacote","1");
    formData.append("pacote_id",pacoteId);
    ids.forEach(id=>formData.append("categorias[]",id));
    formData.append("ordem_categorias",JSON.stringify(ordem));

    fetch("./api/pacotes.php",{method:"POST",body:formData})
    .then(res=>res.json())
    .then(data=>Swal.fire(data.title,data.msg,data.icon));
}

// Criar novo pacote
document.getElementById("formNovoPacote").addEventListener("submit", function(e){
    e.preventDefault();
    const formData = new FormData(this);
    formData.append("add_pacote_action","1");
    fetch("./api/pacotes.php",{method:"POST",body:formData})
    .then(res=>res.json())
    .then(data=>{ if(data.reload) location.reload(); else Swal.fire(data.title,data.msg,data.icon); });
});

// Excluir pacote
function confirmarExclusao(){
    const select=document.getElementById("pacote_a_excluir");
    const pacoteId=select.value;
    if(!pacoteId) return Swal.fire("Atenção!","Selecione um pacote.","warning");
    const pacoteNome=select.options[select.selectedIndex].text;
    Swal.fire({
        title:`Excluir ${pacoteNome}?`,
        text:"Esta ação não pode ser desfeita.",
        icon:"warning",
        showCancelButton:true,
        confirmButtonColor:"#ef4444",
        cancelButtonColor:"#3b82f6",
        confirmButtonText:"Sim, excluir!",
        cancelButtonText:"Cancelar"
    }).then(result=>{
        if(!result.isConfirmed) return;
        const formData=new FormData();
        formData.append("excluir_pacote","1");
        formData.append("pacote_id",pacoteId);
        fetch("./api/pacotes.php",{method:"POST",body:formData})
        .then(res=>res.json())
        .then(data=>{
            Swal.fire(data.title,data.msg,data.icon).then(()=>{if(data.icon==="success") location.reload();});
        });
    });
}

window.addEventListener("DOMContentLoaded", carregarPacotes);
</script>
</body>
</html>