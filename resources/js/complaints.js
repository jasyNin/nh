// Функция для показа уведомлений
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-message ${type} show`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Обработка отправки жалоб
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.complaint-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const complaintableId = this.dataset.complaintableId;
            const complaintableType = this.dataset.complaintableType;
            
            const data = {
                complaintable_id: complaintableId,
                complaintable_type: complaintableType,
                type: formData.get('type'),
                reason: formData.get('reason'),
                target_type: formData.get('target_type')
            };
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Ошибка при отправке жалобы');
                }
                
                // Закрываем модальное окно
                const modal = this.closest('.modal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
                
                // Показываем уведомление об успехе
                showToast(result.message);
                
                // Очищаем форму
                this.reset();
                
            } catch (error) {
                console.error('Ошибка:', error);
                showToast(error.message || 'Произошла ошибка при отправке жалобы', 'error');
            }
        });
    });
}); 