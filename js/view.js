document.addEventListener("DOMContentLoaded", function () {
    const tableView = document.querySelector("[data-table]");
    const cardsView = document.querySelector("[data-cards]");
    const tableIcon = document.getElementById("tableIcon");
    const cardsIcon = document.getElementById("cardsIcon");

    function showTableView() {
        tableView.style.display = "block";
        cardsView.style.display = "none";
        localStorage.setItem("viewMode", "table");
    }

    function showCardsView() {
        tableView.style.display = "none";
        cardsView.style.display = "block";
        localStorage.setItem("viewMode", "cards");
    }

    tableIcon.addEventListener("click", function (event) {
        event.preventDefault();
        showTableView();
    });

    cardsIcon.addEventListener("click", function (event) {
        event.preventDefault();
        showCardsView();
    });

    const savedView = localStorage.getItem("viewMode");
    if (savedView === "cards") {
        showCardsView();
    } else {
        showTableView();
    }
});



document.addEventListener("click", function (e) {
    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modal-title");
    const modalImage = document.getElementById("modal-image");
    const modalSynopsis = document.getElementById("modal-synopsis");
    const modalGenre = document.getElementById("modal-genre");
    const modalDuration = document.getElementById("modal-duration");
    const modalDate = document.getElementById("modal-date");
    const modalTrailer = document.getElementById("modal-trailer");

    if (e.target.classList.contains("ver-detalhes")) {
        const card = e.target.closest(".card");

        if (card) {
            modalTitle.textContent = card.querySelector("h4").textContent;
            modalImage.src = card.querySelector("img").src;
            modalSynopsis.textContent = card.querySelector(".sinopse").textContent;
            modalGenre.textContent = card.querySelector(".genero").textContent;
            modalDuration.textContent = card.querySelector(".duracao").textContent;
            modalDate.textContent = card.querySelector(".date").textContent;

            let link = card.querySelector(".trailer_link").textContent.trim();
            console.log("Link original:", link);

            // Extraindo o ID do vídeo e formatando corretamente a URL para o iframe
            const videoId = extractYouTubeVideoId(link);

            if (videoId) {
                const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
                modalTrailer.setAttribute("src", embedUrl);
                console.log("URL de incorporação gerada:", embedUrl);
            } else {
                console.error("Erro: ID do vídeo não encontrado.");
                modalTrailer.setAttribute("src", ""); // Limpa o iframe se o ID não for encontrado
            }

            // Exibe o modal
            modal.classList.remove("hidden");
        }
    }

    // Fecha o modal e limpa o vídeo para parar a reprodução
    if (e.target.id === "close-modal" || e.target === modal) {
        modal.classList.add("hidden");
        modalTrailer.setAttribute("src", ""); // Parar o vídeo ao fechar o modal
    }
});

// Função para extrair o ID do vídeo corretamente e garantir que seja no formato /embed/
function extractYouTubeVideoId(url) {
    // Converte links móveis para o padrão
    url = url.replace("m.youtube.com", "www.youtube.com");

    // Expressão regular para capturar o ID do vídeo
    const regex = /(?:youtube\.com\/(?:.*[?&]v=|embed\/|v\/)|youtu\.be\/)([^"&?\/\s]{11})/;
    const match = url.match(regex);

    return match ? match[1] : null;
}

document.querySelectorAll("#btn-modal").forEach(button => {
    console.log("Botão encontrado:", button); // Verifica se os botões existem

    button.addEventListener("click", (e) => {
        console.log("Clicou no botão Ver detalhes!");
    });
});




document.querySelectorAll("#btn-modal").forEach(button => {
    console.log("Botão encontrado:", button); // Verifica se os botões existem

    button.addEventListener("click", (e) => {
        console.log("Clicou no botão Ver detalhes!");
    });
});

