<?php
session_start();
include('config/conexao.php');

// SEGURANÇA: Se não estiver logado, manda para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

// Busca os dados do cliente
$id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM cadastros WHERE id = $id";
$query = $conn->query($sql);

// Se o usuário foi deletado mas a sessão continua ativa, evita erro
if ($query->num_rows == 0) {
    session_destroy();
    header("Location: login.html");
    exit;
}

$cliente = $query->fetch_assoc();

// --- LÓGICA DE PEDIDOS (ADICIONADA) ---
// Busca os pedidos ordenados do mais recente para o mais antigo
$sql_pedidos = "SELECT * FROM pedidos WHERE id_cliente = $id ORDER BY data_pedido DESC";
$query_pedidos = $conn->query($sql_pedidos);

// Verifica se veio redirecionado para a aba pedidos (ex: após comprar)
$aba_ativa = isset($_GET['aba']) && $_GET['aba'] == 'pedidos' ? 'pedidos' : 'dados';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - Pkz Sneakers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Roboto', sans-serif; }
        
        /* Sidebar (Menu Lateral) */
        .sidebar-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .user-header { background: #000; color: #fff; border-radius: 10px 10px 0 0; padding: 30px 20px; text-align: center; }
        .user-avatar { width: 100px; height: 100px; border-radius: 50%; border: 4px solid #fff; object-fit: cover; margin-bottom: 10px; }
        
        .list-group-item { border: none; padding: 15px 20px; font-weight: 500; color: #555; transition: all 0.3s; }
        .list-group-item:hover { background-color: #f0f0f0; color: #000; padding-left: 25px; }
        .list-group-item.active { background-color: #000; color: #fff; border-color: #000; }
        .list-group-item i { margin-right: 10px; width: 20px; text-align: center; }

        /* Conteúdo Principal */
        .content-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 30px; background: #fff; min-height: 400px; }
        .section-title { font-weight: bold; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }
        
        /* Botões */
        .btn-black { background-color: #000; color: #fff; border: 1px solid #000; }
        .btn-black:hover { background-color: #333; color: #fff; }
        .btn-outline-black { border: 1px solid #000; color: #000; background: transparent; }
        .btn-outline-black:hover { background: #000; color: #fff; }

        /* Pedidos */
        .order-item { border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 15px; transition: 0.2s; }
        .order-item:hover { border-color: #000; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark px-4 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">PKZ SNEAKERS</a>
            <a href="index.html" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-arrow-left me-2"></i> Voltar à Loja</a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="sidebar-card bg-white">
                    <div class="user-header">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($cliente['nome']); ?>&background=333&color=fff&size=128" class="user-avatar">
                        <h5 class="mb-0"><?php echo htmlspecialchars($cliente['nome']); ?></h5>
                        <small>Membro Pkz Club</small>
                    </div>
                    <div class="list-group list-group-flush py-2" id="myList" role="tablist">
                        <a class="list-group-item list-group-item-action <?php echo $aba_ativa == 'dados' ? 'active' : ''; ?>" data-bs-toggle="list" href="#dados" role="tab">
                            <i class="fa-solid fa-user"></i> Meus Dados
                        </a>
                        <a class="list-group-item list-group-item-action <?php echo $aba_ativa == 'pedidos' ? 'active' : ''; ?>" data-bs-toggle="list" href="#pedidos" role="tab">
                            <i class="fa-solid fa-box-open"></i> Meus Pedidos
                        </a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#enderecos" role="tab">
                            <i class="fa-solid fa-location-dot"></i> Endereços
                        </a>
                        <a class="list-group-item list-group-item-action text-danger mt-3" href="controllers/logout.php">
                            <i class="fa-solid fa-right-from-bracket"></i> Sair da Conta
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="tab-content">
                    
                    <div class="tab-pane fade <?php echo $aba_ativa == 'dados' ? 'show active' : ''; ?>" id="dados" role="tabpanel">
                        <div class="content-card">
                            <h4 class="section-title">Informações Pessoais</h4>
                            <form>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label text-muted">Nome Completo</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($cliente['nome']); ?>" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label text-muted">E-mail de Acesso</label>
                                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($cliente['email']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Membro Desde</label>
                                        <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($cliente['criado_em'])); ?>" readonly>
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button type="button" class="btn btn-outline-black disabled">Editar Dados (Em breve)</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade <?php echo $aba_ativa == 'pedidos' ? 'show active' : ''; ?>" id="pedidos" role="tabpanel">
                        <div class="content-card">
                            <h4 class="section-title">Histórico de Pedidos</h4>
                            
                            <?php if ($query_pedidos && $query_pedidos->num_rows > 0): ?>
                                <?php while($pedido = $query_pedidos->fetch_assoc()): ?>
                                    <div class="order-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1 fw-bold">Pedido #<?php echo $pedido['id']; ?></h5>
                                                <small class="text-muted">
                                                    <i class="fa-regular fa-calendar me-1"></i>
                                                    <?php echo date('d/m/Y \à\s H:i', strtotime($pedido['data_pedido'])); ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <h5 class="mb-1">R$ <?php echo number_format($pedido['valor_total'], 2, ',', '.'); ?></h5>
                                                <span class="badge bg-success status-badge">
                                                    <?php echo $pedido['status']; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-cart-arrow-down fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Você ainda não fez nenhum pedido.</h5>
                                    <a href="produtos.php" class="btn btn-black mt-3">Ir às Compras</a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="enderecos" role="tabpanel">
                        <div class="content-card">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                <h4 class="m-0">Meus Endereços</h4>
                                <button class="btn btn-sm btn-black"><i class="fa-solid fa-plus"></i> Novo</button>
                            </div>

                            <div class="alert alert-secondary">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Cadastre seu endereço para agilizar suas compras futuras.
                            </div>
                            
                            <div class="card p-3 mb-3 border-dark">
                                <div class="d-flex justify-content-between">
                                    <strong><i class="fa-solid fa-house"></i> Principal</strong>
                                    <span class="badge bg-dark">Padrão</span>
                                </div>
                                <p class="mb-1 mt-2 text-muted">
                                    Endereço ainda não cadastrado.<br>
                                    Brasil
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>