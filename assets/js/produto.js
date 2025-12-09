document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    if (productId) {
        fetch(`/api/produtos/${productId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('produto-nome').textContent = data.nome;
                document.getElementById('produto-tamanho').textContent = data.tamanho;
                document.getElementById('produto-valor').textContent = `R$ ${data.valor}`;
                document.getElementById('produto-valorTotal').textContent = data.valorTotal;
            })
            .catch(error => {
                console.error('Erro ao carregar dados do produto:', error);
            });
    } else {
        console.error('ID do produto não especificado na URL');
    }
});
