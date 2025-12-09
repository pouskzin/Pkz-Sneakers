"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var urlParams = new URLSearchParams(window.location.search);
  var productId = urlParams.get('id');

  if (productId) {
    fetch("/api/produtos/".concat(productId)).then(function (response) {
      return response.json();
    }).then(function (data) {
      document.getElementById('produto-nome').textContent = data.nome;
      document.getElementById('produto-tamanho').textContent = data.tamanho;
      document.getElementById('produto-valor').textContent = "R$ ".concat(data.valor);
      document.getElementById('produto-valorTotal').textContent = data.valorTotal;
    })["catch"](function (error) {
      console.error('Erro ao carregar dados do produto:', error);
    });
  } else {
    console.error('ID do produto não especificado na URL');
  }
});
//# sourceMappingURL=produto.dev.js.map
