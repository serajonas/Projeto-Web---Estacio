const apiBase = 'api.php';

function apiUrl(resource, params = {}) {
    const query = new URLSearchParams({ resource, ...params }).toString();
    return `${apiBase}?${query}`;
}

const endpoints = {
    all: apiBase,
    descricao: apiUrl('descricao'),
    projetos: apiUrl('projetos')
};

const listaProjetos = document.getElementById('listaProjetos');
const descricaoBanner = document.getElementById('descricaoBanner');
const sobreDescricao = document.getElementById('sobreDescricao');
const navLinks = document.querySelectorAll('#cabecalho nav a');
const pages = document.querySelectorAll('.page');
const projetoFormContainer = document.getElementById('projetoFormContainer');
const descricaoForm = document.getElementById('descricaoForm');
const toggleProjetoForm = document.getElementById('toggleProjetoForm');
const toggleDescricaoForm = document.getElementById('toggleDescricaoForm');
const maisFormContainer = document.getElementById('maisFormContainer');
const maisForm = document.getElementById('maisForm');
const toggleMaisForm = document.getElementById('toggleMaisForm');
const maisHabilidades = document.getElementById('maisHabilidades');
const maisContato = document.getElementById('maisContato');
const maisObjetivo = document.getElementById('maisObjetivo');
const maisHabilidadesInput = document.getElementById('maisHabilidadesInput');
const maisContatoInput = document.getElementById('maisContatoInput');
const maisObjetivoInput = document.getElementById('maisObjetivoInput');
const scrollProjects = document.getElementById('scrollProjects');

const moreStorageKey = 'maisInfoData';

function getMaisData() {
    const saved = localStorage.getItem(moreStorageKey);
    if (saved) {
        try {
            return JSON.parse(saved);
        } catch {
            // ignore invalid data
        }
    }

    return {
        habilidades: 'Analista de Suporte de TI | Windows | Linux | Windows Server | ITIL | Infraestrutura | HTML | CSS | JavaScript | MySQL | Python | Data Science.',
        contato: 'LinkedIn: linkedin.com/in/jonasserafim | WhatsApp: (19) 98712-2278 | Email: serajonas@gmail.com | GitHub: github.com/serajonas',
        objetivo: 'Atuar em desenvolvimento e suporte técnico com foco em soluções web e infraestrutura de TI.'
    };
}

function saveMaisData(data) {
    localStorage.setItem(moreStorageKey, JSON.stringify(data));
}

function renderMaisInfo() {
    const data = getMaisData();
    maisHabilidades.innerText = data.habilidades;
    maisContato.innerText = data.contato;
    maisObjetivo.innerText = data.objetivo;
    maisHabilidadesInput.value = data.habilidades;
    maisContatoInput.value = data.contato;
    maisObjetivoInput.value = data.objetivo;
}

function showPage(pageId) {
    pages.forEach(page => page.classList.toggle('active', page.id === pageId));
    navLinks.forEach(link => link.classList.toggle('active', link.dataset.page === pageId));

    if (pageId === 'projectsPage' || pageId === 'aboutPage') {
        carregarDados();
    }
}

function toggleVisibility(element, button, showText, hideText) {
    const isHidden = element.classList.toggle('hidden');
    button.innerText = isHidden ? showText : hideText;
}

async function requestJson(url, options = {}) {
    const response = await fetch(url, {
        cache: 'no-store',
        ...options
    });

    if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`HTTP ${response.status} - ${errorText}`);
    }

    return response.json();
}

function getProjectImageUrl(projeto) {
    const seed = `${projeto.categoria || projeto.nome}`.replace(/\s+/g, '-').toLowerCase();
    return `https://picsum.photos/seed/${encodeURIComponent(seed)}/640/360`;
}

function renderProjects(projetos) {
    if (!Array.isArray(projetos) || projetos.length === 0) {
        return '<p class="sem-projetos">Nenhum projeto disponível no momento</p>';
    }

    return projetos.map((projeto, indice) => `
        <div class="card" style="animation-delay: ${indice * 0.1}s">
            <div class="card-image">
                <img src="${getProjectImageUrl(projeto)}" alt="Imagem de ${projeto.nome}" loading="lazy">
            </div>
            <div class="card-header">
                <h3>${projeto.nome}</h3>
                <span class="badge">${projeto.categoria || 'Projeto'}</span>
            </div>
            <p class="card-descricao">${projeto.descricao}</p>
            <div class="card-footer">
                <button class="btn-excluir" data-id="${projeto.id}">Apagar</button>
                <span class="data">${projeto.data_projeto || 'Data não disponível'}</span>
            </div>
        </div>
    `).join('');
}

async function carregarDados() {
    try {
        if (listaProjetos) {
            listaProjetos.innerHTML = '<div class="loader"><div class="spinner"></div><p>Carregando informações...</p></div>';
        }

        const dados = await requestJson(endpoints.all);
        if (!dados || typeof dados.descricao !== 'string' || !Array.isArray(dados.projetos)) {
            throw new Error('Dados inválidos recebidos do servidor');
        }

        if (descricaoBanner) {
            descricaoBanner.style.opacity = '0';
            setTimeout(() => {
                descricaoBanner.innerText = dados.descricao;
                descricaoBanner.style.transition = 'opacity 0.5s ease-in-out';
                descricaoBanner.style.opacity = '1';
            }, 100);
        }

        if (sobreDescricao) {
            sobreDescricao.style.opacity = '0';
            setTimeout(() => {
                sobreDescricao.innerText = dados.descricao;
                sobreDescricao.style.transition = 'opacity 0.5s ease-in-out';
                sobreDescricao.style.opacity = '1';
            }, 100);
        }

        if (listaProjetos) {
            listaProjetos.style.opacity = '0';
            setTimeout(() => {
                listaProjetos.innerHTML = renderProjects(dados.projetos);
                listaProjetos.style.transition = 'opacity 0.5s ease-in-out';
                listaProjetos.style.opacity = '1';
            }, 100);
        }
    } catch (erro) {
        console.error('✗ Erro ao carregar dados:', erro);
        if (listaProjetos) {
            listaProjetos.innerHTML = `<div class="erro"><i class="fas fa-exclamation-circle"></i><p>Não foi possível carregar os dados: ${erro.message}</p></div>`;
        }
    }
}

async function enviarDescricao(event) {
    event.preventDefault();

    const descricaoInput = document.getElementById('descricaoInput');
    const novoTexto = descricaoInput.value.trim();

    if (!novoTexto) {
        alert('Informe uma descrição válida.');
        return;
    }

    try {
        await requestJson(endpoints.descricao, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ descricao: novoTexto })
        });

        descricaoInput.value = '';
        carregarDados();
    } catch (erro) {
        console.error(erro);
        alert('Não foi possível atualizar a descrição.');
    }
}

async function criarProjeto(event) {
    event.preventDefault();

    const nome = document.getElementById('nomeProjeto').value.trim();
    const descricao = document.getElementById('descricaoProjeto').value.trim();
    const categoria = document.getElementById('categoriaProjeto').value.trim();
    const data = document.getElementById('dataProjeto').value.trim();

    if (!nome || !descricao || !categoria || !data) {
        alert('Preencha todos os campos do projeto.');
        return;
    }

    try {
        await requestJson(endpoints.projetos, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nome, descricao, categoria, data })
        });

        document.getElementById('projetoForm').reset();
        carregarDados();
    } catch (erro) {
        console.error(erro);
        alert('Não foi possível salvar o projeto.');
    }
}

async function deletarProjeto(id) {
    if (!confirm('Deseja realmente apagar este projeto?')) {
        return;
    }

    try {
        await requestJson(apiUrl('projetos', { id }), {
            method: 'DELETE'
        });

        carregarDados();
    } catch (erro) {
        console.error(erro);
        alert('Não foi possível apagar o projeto.');
    }
}

function registrarEventos() {
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            showPage(link.dataset.page);
        });
    });

    toggleProjetoForm.addEventListener('click', () => {
        toggleVisibility(projetoFormContainer, toggleProjetoForm, 'Mostrar formulário de projeto', 'Ocultar formulário de projeto');
    });

    toggleDescricaoForm.addEventListener('click', () => {
        toggleVisibility(descricaoForm, toggleDescricaoForm, 'Editar descrição', 'Ocultar edição');
    });

    toggleMaisForm.addEventListener('click', () => {
        toggleVisibility(maisFormContainer, toggleMaisForm, 'Editar informações de Mais', 'Ocultar edição de Mais');
    });

    document.getElementById('projetoForm').addEventListener('submit', criarProjeto);
    descricaoForm.addEventListener('submit', enviarDescricao);
    maisForm.addEventListener('submit', salvarMaisInfo);

    listaProjetos.addEventListener('click', (event) => {
        const button = event.target.closest('.btn-excluir');
        if (button) {
            const id = button.dataset.id;
            if (id) {
                deletarProjeto(id);
            }
        }
    });

    if (scrollProjects) {
        scrollProjects.addEventListener('click', (event) => {
            event.preventDefault();
            showPage('projectsPage');
        });
    }
}

function salvarMaisInfo(event) {
    event.preventDefault();
    const data = {
        habilidades: maisHabilidadesInput.value.trim(),
        contato: maisContatoInput.value.trim(),
        objetivo: maisObjetivoInput.value.trim()
    };

    saveMaisData(data);
    renderMaisInfo();
    toggleVisibility(maisFormContainer, toggleMaisForm, 'Editar informações de Mais', 'Ocultar edição de Mais');
}

document.addEventListener('DOMContentLoaded', () => {
    registrarEventos();
    carregarDados();
    renderMaisInfo();
    setInterval(carregarDados, 10000);
});
