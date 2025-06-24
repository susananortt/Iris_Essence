<?php
    // Inicia a sessão do usuário
    session_start();
    require_once 'conexao.php';
    
    date_default_timezone_set('America/Sao_Paulo');
    
    $hoje = date('Y-m-d');
    $ano = isset(// Dados recebidos via URL
    $_GET['ano']) ? (int)// Dados recebidos via URL
    $_GET['ano'] : date('Y');
    $mes = isset(// Dados recebidos via URL
    $_GET['mes']) ? (int)// Dados recebidos via URL
    $_GET['mes'] : date('m');
    if ($mes < 1) { $mes = 12; $ano--; }
    if ($mes > 12) { $mes = 1; $ano++; }
    
    // Função para formatar a data para o padrão BR (d/m/Y)
    function brDate($date) {
        return date('d/m/Y', strtotime($date));
    }
    
    // Gera array dos dias do mês
    $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $primeiroDiaSemana = date('w', strtotime("$ano-$mes-01")); // 0=domingo ... 6=sábado
    
    // Ajuste para calendário que inicia na segunda-feira
    // Se quiser domingo como primeiro dia, mude aqui para usar 0 como domingo.
    $primeiroDiaSemana = $primeiroDiaSemana == 0 ? 7 : $primeiroDiaSemana; // domingo=7
    
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Agenda Mensal - Íris Essence</title>
<link rel="icon" href="../imgs/logo.jpg" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/style.css" />
<style>
.calendario {
    max-width: 1100px; /* Aumentado de 900 para 1050 */
    margin: auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 15px #ccc;
}
table.calendar {
    width: 100%;
    border-collapse: collapse;
    user-select: none;
}
table.calendar th, table.calendar td {
    border: 1px solid #ddd;
    vertical-align: top;
    height: 80px;
    padding: 5px;
}
table.calendar th {
    background: #eee;
    text-align: center;
}
table.calendar td.dia {
    cursor: pointer;
    position: relative;
    background: #fefefe;
    transition: background-color 0.3s ease;
}
table.calendar td.dia:hover {
    background: #f0f8ff;
}
table.calendar td.dia.disabled {
    background: #f5f5f5;
    color: #aaa;
    cursor: default;
}
.numero-dia {
    font-weight: bold;
    font-size: 1.2rem;
    margin-bottom: 4px;
}
.agendamento-resumo {
    font-size: 0.85rem;
    background: #d4edda;
    color: #155724;
    padding: 2px 5px;
    border-radius: 4px;
    margin-bottom: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.agendamento-resumo.busy {
    background: #f8d7da;
    color: #721c24;
}
.nav-mes {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.modal-body {
    max-height: 60vh;
    overflow-y: auto;
}

</style>
</head>
<body class ="cadastro-fundo" >
<header>
    <nav>
        <ul>
            <a href="../html/index.html">
                <img src="../imgs/logo.jpg" class="logo" alt="Logo">
            </a>
            <li><a href="../html/index.html">HOME</a></li>
            <li>
                <a href="#">PROCEDIMENTOS FACIAIS</a>
                <div class="submenu">
                    <a href="../html/limpezapele.html">Limpeza de Pele</a>
                    <a href="../html/labial.html">Preenchimento labial</a>
                    <a href="../html/microagulhamento.html">Microagulhamento</a>
                    <a href="../html/botoxfacial.html">Botox</a>
                    <a href="../html/acne.html">Tratamento para Acne</a>
                    <a href="../html/rinomodelacao.html">Rinomodelação</a>
                </div>
            </li>
            <li>
                <a href="#">PROCEDIMENTOS CORPORAIS</a>
                <div class="submenu">
                    <a href="../html/massagemmodeladora.html">Massagem Modeladora</a>
                    <a href="../html/drenagemlinfatica.html">Drenagem Linfática</a>
                    <a href="../html/depilacaolaser.html">Depilação a Laser</a>
                    <a href="../html/depilacaocera.html">Depilação de cera</a>
                    <a href="../html/massagemrelaxante.html">Massagem Relaxante</a>
                </div>
            </li>
            <li><a href="../html/produtos.html">PRODUTOS</a></li>|
            <li><a href="../html/login.php">LOGIN</a></li>|
            <li><a href="../html/cadastro.html">CADASTRO</a></li>|

            <div class="logout">
                <form action = "logout.php" method= "POST">
                <button type="submit">Logout</button>
            </div>
        </ul>
    </nav>
</header>
<br>
<div class="calendario">
<div class="nav-mes">
<a href="?ano=<?= $mes == 1 ? $ano-1 : $ano ?>&mes=<?= $mes == 1 ? 12 : $mes-1 ?>" class="btn btn-outline-primary">
<i class="fa fa-chevron-left"></i> Mês Anterior
</a>
<?php
    $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $data = new DateTime("$ano-$mes-01");
?>
<h3><?= ucfirst($formatter->format($data)) ?></h3>
<a href="?ano=<?= $mes == 12 ? $ano+1 : $ano ?>&mes=<?= $mes == 12 ? 1 : $mes+1 ?>" class="btn btn-outline-primary">
Próximo Mês <i class="fa fa-chevron-right"></i>
</a>
</div>

<table class="calendar table table-bordered">
<thead>
<tr>
<th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th><th>Dom</th>
</tr>
</thead>
<tbody>
<?php
    $diaAtual = 1;
    $totalCelulas = 42; // 6 semanas x 7 dias
    for ($linha = 1; $linha <= 6; $linha++) {
        echo "<tr>";
        for ($coluna = 1; $coluna <= 7; $coluna++) {
            $celula = ($linha - 1) * 7 + $coluna;
            $dataCelula = null;
            $classe = "dia";
            
            // Calcular se esta célula deve conter um dia válido do mês
            if ($celula >= $primeiroDiaSemana && $diaAtual <= $diasNoMes) {
                $dataCelula = sprintf('%04d-%02d-%02d', $ano, $mes, $diaAtual);
                
                // Disable dias antes do hoje
                $disabled = ($dataCelula < $hoje);
                
                // Desabilitar finais de semana (sábado e domingo)
                if ($coluna == 6 || $coluna == 7) {
                    $disabled = true;
                }
                
                $classe .= $disabled ? " disabled" : "";
                
                $diaNumero = $diaAtual;
                $diaAtual++;
            } else {
                $classe = "disabled";
                $diaNumero = "";
            }
        ?>
        <td class="<?= $classe ?>" <?= $dataCelula && !$disabled ? "data-dia='$dataCelula'" : '' ?>>
        <div class="numero-dia"><?= $diaNumero ?></div>
        <div class="agendamentos-resumo"></div>
        </td>
        <?php
        }
        echo "</tr>";
    }
?>
</tbody>
</table>
</div>

<!-- Modal detalhes do dia -->
<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="modalDetalhesLabel">Agendamentos do dia</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>
<div class="modal-body">
<div id="detalhesContent">
<p>Carregando...</p>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
</div>
</div>
</div>
</div>
<br>

<button type="button" class="voltar-button" onclick="window.location.href='principal.php'">Voltar</button>

<footer class="l-footer">&copy; 2025 Íris Essence - Beauty Clinic. Todos os direitos reservados.</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Ao carregar a página, para cada dia habilitado, buscar agendamentos resumidos via AJAX
document.querySelectorAll('td.dia[data-dia]').forEach(td => {
    const dataDia = td.getAttribute('data-dia');
    fetch('ajax_agenda.php?action=resumo&data=' + dataDia)
    .then(resp => resp.json())
    .then(data => {
        const container = td.querySelector('.agendamentos-resumo');
        container.innerHTML = '';
        if(data.length === 0) {
            container.innerHTML = '<small><i>Nenhum agendamento</i></small>';
        } else {
            data.forEach(ag => {
                const div = document.createElement('div');
                div.className = 'agendamento-resumo';
                div.textContent = ag.hora.substring(0,5) + " - " + ag.nome + " (" + ag.procedimento + ")";
                container.appendChild(div);
            });
        }
    });
});

// Abrir modal e carregar agendamentos detalhados ao clicar em um dia
const modalDetalhes = new bootstrap.Modal(document.getElementById('modalDetalhes'));
document.querySelectorAll('td.dia[data-dia]').forEach(td => {
    td.addEventListener('click', () => {
        const dataDia = td.getAttribute('data-dia');
        document.getElementById('modalDetalhesLabel').textContent = 'Agendamentos de ' + dataDia.split('-').reverse().join('/');
        const content = document.getElementById('detalhesContent');
        content.innerHTML = 'Carregando...';
        
        fetch('ajax_agenda.php?action=detalhes&data=' + dataDia)
        .then(resp => resp.text())
        .then(html => {
            content.innerHTML = html;
            attachFormHandlers(); // para formularios dinâmicos
        });
        modalDetalhes.show();
    });
});

function attachFormHandlers() {
    // Formulário para cadastrar novo agendamento
    const formNovo = document.getElementById('form-novo-agendamento');
    if (formNovo) {
        formNovo.addEventListener('submit', e => {
            e.preventDefault();
            const fd = new FormData(formNovo);
            fetch('ajax_agenda.php?action=cadastrar', {
                method: 'POST',
                body: fd
            })
            .then(resp => resp.json())
            .then(json => {
                alert(json.msg);
                if (json.sucesso) location.reload();
            });
        });
    }
    
    // Formularios para excluir
    document.querySelectorAll('.btn-excluir').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            if (!confirm('Confirma exclusão?')) return;
            const id = btn.getAttribute('data-id');
            fetch('ajax_agenda.php?action=excluir&id=' + id)
            .then(resp => resp.json())
            .then(json => {
                alert(json.msg);
                if (json.sucesso) location.reload();
            });
        });
    });
    
    // Formularios para editar (mostrar formulario)
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            fetch('ajax_agenda.php?action=editar&id=' + id)
            .then(resp => resp.text())
            .then(html => {
                document.getElementById('detalhesContent').innerHTML = html;
                attachFormHandlers();
            });
        });
    });
    
    // Formulario de edição (submissão)
    const formEditar = document.getElementById('form-editar-agendamento');
    if (formEditar) {
        formEditar.addEventListener('submit', e => {
            e.preventDefault();
            const fd = new FormData(formEditar);
            fetch('ajax_agenda.php?action=alterar', {
                method: 'POST',
                body: fd
            })
            .then(resp => resp.json())
            .then(json => {
                alert(json.msg);
                if (json.sucesso) location.reload();
            });
        });
    }
}
</script>

</body>
</html>
