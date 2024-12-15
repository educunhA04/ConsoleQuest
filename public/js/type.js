function openTypeModal() {
    document.querySelector('.typeModal').style.display = 'block';
}

function closeTypeModal() {
    document.querySelector('.typeModal').style.display = 'none';
}

// Fecha o modal se clicar fora dele
window.onclick = function(event) {
    const modal = document.querySelector('.typeModal');
    if (event.target === modal) {
        closeTypeModal();
    }
};

