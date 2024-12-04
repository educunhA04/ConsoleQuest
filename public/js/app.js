document.addEventListener('DOMContentLoaded', function () {
    // Adiciona eventos de clique em todas as reviews
    const reviews = document.querySelectorAll('.review');

    reviews.forEach(review => {
        review.addEventListener('click', function () {
            // Verifica se o formulário de edição está visível
            const editForm = review.querySelector('.edit-form');
            if (editForm && editForm.style.display === 'block') {
                // Se o formulário de edição estiver visível, não mostra os botões
                return;
            }

            // Esconde botões em todas as outras reviews
            reviews.forEach(r => {
                const buttons = r.querySelectorAll('.edit-btn, .delete-btn');
                buttons.forEach(btn => btn.style.display = 'none');
            });

            // Exibe os botões da review clicada
            const buttons = this.querySelectorAll('.edit-btn, .delete-btn');
            buttons.forEach(btn => btn.style.display = 'inline-block');
        });
    });

    // Esconde os botões quando o usuário clica fora das reviews
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.review')) {
            reviews.forEach(r => {
                const buttons = r.querySelectorAll('.edit-btn, .delete-btn');
                buttons.forEach(btn => btn.style.display = 'none');
            });
        }
    });
});

function showEditForm(reviewId) {
    const reviewElement = document.getElementById(`review-${reviewId}`);
    if (reviewElement) {
        const editForm = reviewElement.querySelector('.edit-form');
        const editButton = reviewElement.querySelector('.edit-btn');
        const deleteButton = reviewElement.querySelector('.delete-btn');

        if (editForm) {
            // Exibe o formulário de edição
            editForm.style.display = 'block';

            // Esconde os botões de editar e excluir
            if (editButton) editButton.style.display = 'none';
            if (deleteButton) deleteButton.style.display = 'none';
        }
    }
}

function hideEditForm(reviewId) {
    const reviewElement = document.getElementById(`review-${reviewId}`);
    if (reviewElement) {
        const editForm = reviewElement.querySelector('.edit-form');
        const editButton = reviewElement.querySelector('.edit-btn');
        const deleteButton = reviewElement.querySelector('.delete-btn');

        if (editForm) {
            // Esconde o formulário de edição
            editForm.style.display = 'none';

            // Reexibe os botões de editar e excluir
            if (editButton) editButton.style.display = 'inline-block';
            if (deleteButton) deleteButton.style.display = 'inline-block';
        }
    }
}
