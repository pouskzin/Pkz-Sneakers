document.addEventListener("DOMContentLoaded", function() {
    
    console.log("1. Script de sessão carregado."); // Teste 1

    const linkUsuario = document.getElementById('link-usuario');

    if (linkUsuario) {
        console.log("2. Ícone de usuário encontrado no HTML."); // Teste 2

        // Faz a pergunta ao servidor
        fetch('controllers/check_session.php')
            .then(response => {
                console.log("3. Resposta recebida do servidor."); // Teste 3
                return response.json();
            })
            .then(data => {
                console.log("4. Dados recebidos:", data); // Teste 4 - Mostra o que o PHP respondeu

                if (data.logado === true) {
                    console.log("5. USUÁRIO ESTÁ LOGADO! Mudando ícone..."); // Teste 5
                    
                    linkUsuario.href = 'perfil.php';
                    linkUsuario.style.color = '#28a745'; 
                    
                    const primeiroNome = data.nome.split(' ')[0]; 
                    
                    linkUsuario.innerHTML = `
                        <i class="fa-solid fa-user-check"></i> 
                        <span style="font-size: 14px; margin-left: 5px; font-weight: bold;">
                            ${primeiroNome}
                        </span>
                    `;
                } else {
                    console.log("5. Usuário NÃO está logado.");
                }
            })
            .catch(error => {
                console.error('ERRO CRÍTICO:', error);
            });
    } else {
        console.error("ERRO: Não encontrei o elemento com id 'link-usuario'");
    }
});