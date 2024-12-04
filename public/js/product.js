function showEditForm(reviewId) {
    alert('Função de edição para a avaliação com ID: ' + reviewId);

    const reviewElement = document.getElementById(`review-${reviewId}`);
    if (reviewElement) {
        const editForm = reviewElement.querySelector('.edit-form');
        if (editForm) {
            editForm.style.display = 'block';
        }
    }
}

function hideEditForm(reviewId) {
    const reviewElement = document.getElementById(`review-${reviewId}`);
    if (reviewElement) {
        const editForm = reviewElement.querySelector('.edit-form');
        if (editForm) {
            editForm.style.display = 'none';
        }
    }
}
