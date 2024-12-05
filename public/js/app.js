document.addEventListener('DOMContentLoaded', function () {

    const editForms = document.querySelectorAll('.edit-form');

    editForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            const formData = new FormData(this);
            const url = this.action;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza o conteúdo da review na página
                        const reviewElement = document.getElementById(`review-${data.review.id}`);
                        reviewElement.querySelector('p').textContent = data.review.description;
                        reviewElement.querySelector('.star-rating').innerHTML = createStars(data.review.rating);
                        hideEditForm(data.review.id);
                    } else {
                        alert('Erro ao salvar a avaliação.');
                    }
                })
                .catch(error => console.error('Erro:', error));
        });
    });

    function createStars(rating) {
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += `<span class="star ${i <= rating ? 'selected' : ''}" data-value="${i}">&#9733;</span>`;
        }
        return starsHtml;
    }
  // Adiciona eventos de clique em todas as reviews
    const reviews = document.querySelectorAll('.review');

    reviews.forEach(review => {
        review.addEventListener('click', function () {
            const isOwner = review.dataset.isOwner === 'true';

            if (!isOwner) {
                return; // Não faz nada se o usuário não for o dono da review
            }

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


    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating-input');

    stars.forEach(star => {
        // Evento ao passar o mouse
        star.addEventListener('mouseover', function () {
            highlightStars(this.dataset.value); // Destaca estrelas até a atual
        });

        // Evento ao tirar o mouse
        star.addEventListener('mouseout', function () {
            highlightStars(ratingInput.value); // Destaca apenas as estrelas selecionadas
        });

        // Evento ao clicar
        star.addEventListener('click', function () {
            ratingInput.value = this.dataset.value; // Salva o valor da avaliação
            highlightStars(this.dataset.value); // Destaca as estrelas até a clicada
        });
    });

    // Função para destacar as estrelas
    function highlightStars(value) {
        stars.forEach(star => {
            if (star.dataset.value <= value) {
                star.classList.add('selected'); // Adiciona cor às estrelas até a atual
            } else {
                star.classList.remove('selected'); // Remove cor das estrelas além da atual
            }
        });
    }
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
