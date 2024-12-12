document.addEventListener('DOMContentLoaded', function () {
    const reviews = document.querySelectorAll('.review');

    reviews.forEach(review => {
        const reportBtn = review.querySelector('.report-btn');
        const reportForm = review.querySelector('.report-form');
        const editForm = review.querySelector('.edit-form');
        const editBtn = review.querySelector('.edit-btn');
        const deleteBtn = review.querySelector('.delete-btn');
        const cancelReportBtn = review.querySelector('.cancel-report-btn');
        const isOwner = review.dataset.isOwner === 'true';

        // Escondendo o botão Report por padrão
        if (reportBtn) reportBtn.style.display = 'none';

        // Configurar botão Report para exibir ao clicar na review
        review.addEventListener('click', function () {
            const reviewIsOwner = review.dataset.isOwner === 'true';

            // Ocultar os botões de outras reviews
            reviews.forEach(r => {
                const otherReportBtn = r.querySelector('.report-btn');
                const otherEditBtn = r.querySelector('.edit-btn');
                const otherDeleteBtn = r.querySelector('.delete-btn');
                
                if (otherReportBtn) otherReportBtn.style.display = 'none';
                if (otherEditBtn) otherEditBtn.style.display = 'none';
                if (otherDeleteBtn) otherDeleteBtn.style.display = 'none';
            });

            // Exibir o botão de Report para a review clicada

            // Mostrar os botões Editar e Excluir caso seja o dono
            if (reviewIsOwner) {
                if (editBtn) editBtn.style.display = 'inline-block';
                if (deleteBtn) deleteBtn.style.display = 'inline-block';
            }
            else{
                if (reportBtn) reportBtn.style.display = 'inline-block';
            }
        });

        // Configurar botão Report
        if (reportBtn) {
            reportBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                hideAllForms();
                if (reportForm) reportForm.style.display = 'block';
            });
        }

        // Configurar o botão Cancelar do formulário Report
        if (cancelReportBtn) {
            cancelReportBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                if (reportForm) reportForm.style.display = 'none';
            });
        }

    });

    // Clique fora para fechar os botões e formulários
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.review')) {
            reviews.forEach(r => {
                const buttons = r.querySelectorAll('.report-btn, .edit-btn, .delete-btn');
                buttons.forEach(btn => btn.style.display = 'none');
            });

            hideAllForms();
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
            hideAllForms();     
            // Show the edit form
            editForm.style.display = 'block';
            // Hide the edit and delete buttons
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
            // Hide the edit form
            editForm.style.display = 'none';

            // Re-show the edit and delete buttons
            if (editButton) editButton.style.display = 'inline-block';
            if (deleteButton) deleteButton.style.display = 'inline-block';
        }
    }
}

// Helper function to hide all forms
function hideAllForms() {
    document.querySelectorAll('.report-form, .edit-form').forEach(form => {
        form.style.display = 'none';
    });
}
