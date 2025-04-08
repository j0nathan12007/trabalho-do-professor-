<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Aulas - Laboratório</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #000000;  /* Alterado de #4CAF50 (verde) para preto */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .horario {
            background-color: #000000;  /* Alterado de #4CAF50 (verde) para preto */
            color: white;
            font-weight: bold;
        }
        .disponivel {
            background-color: #e6e6e6;  /* Cinza claro para disponível */
            cursor: pointer;
        }
            .disponivel:hover {
                background-color: #d4d4d4;  /* Cinza um pouco mais escuro no hover */
            }
        .agendado {
            background-color: #f2dede;
        }
        .reservado {
            background-color: #fcf8e3;
        }
        .form-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #000000;  /* Alterado de #4CAF50 (verde) para preto */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
            button:hover {
                background-color: #333333;  /* Preto mais claro no hover */
            }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
            .close:hover {
                color: black;
            }
    </style>
</head>
<body>
    <!-- O restante do código HTML permanece exatamente o mesmo -->
    <div class="container">
        <h1>Agendamento de Aulas no Laboratório</h1>
        
        <table id="grade">
            <thead>
                <tr>
                    <th>Horário</th>
                    <th>Segunda</th>
                    <th>Terça</th>
                    <th>Quarta</th>
                    <th>Quinta</th>
                    <th>Sexta</th>
                </tr>
            </thead>
            <tbody>
                <!-- As células serão preenchidas pelo JavaScript -->
            </tbody>
        </table>
        
        <div class="form-container">
            <h2>Agendar Aula</h2>
            <div class="form-group">
                <label for="professor">Professor:</label>
                <input type="text" id="professor" required>
            </div>
            <div class="form-group">
                <label for="disciplina">Disciplina:</label>
                <input type="text" id="disciplina" required>
            </div>
            <div class="form-group">
                <label for="turma">Turma:</label>
                <input type="text" id="turma" required>
            </div>
            <div class="form-group">
                <label for="dia">Dia da Semana:</label>
                <select id="dia" required>
                    <option value="">Selecione</option>
                    <option value="1">Segunda-feira</option>
                    <option value="2">Terça-feira</option>
                    <option value="3">Quarta-feira</option>
                    <option value="4">Quinta-feira</option>
                    <option value="5">Sexta-feira</option>
                </select>
            </div>
            <div class="form-group">
                <label for="aula">Aula:</label>
                <select id="aula" required>
                    <option value="">Selecione</option>
                    <option value="1">1ª Aula</option>
                    <option value="2">2ª Aula</option>
                    <option value="3">3ª Aula</option>
                    <option value="4">4ª Aula</option>
                    <option value="5">5ª Aula</option>
                </select>
            </div>
            <button id="agendar">Agendar Aula</button>
        </div>
    </div>
    
    <!-- Modal para confirmar agendamento -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirmar Agendamento</h2>
            <p id="modalText"></p>
            <button id="confirmBtn">Confirmar</button>
            <button id="cancelBtn">Cancelar</button>
        </div>
    </div>
    
    <script>
        // Dados de agendamento
        let agendamentos = {
            // Estrutura: dia_aula: {professor, disciplina, turma}
            // Exemplo: "1_2": {professor: "João", disciplina: "Matemática", turma: "A1"}
        };
        
        // Inicializar a grade
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.querySelector('#grade tbody');
            
            // Criar 5 linhas (aulas)
            for (let aula = 1; aula <= 5; aula++) {
                const tr = document.createElement('tr');
                
                // Coluna de horário
                const th = document.createElement('th');
                th.textContent = `${aula}ª Aula`;
                th.className = 'horario';
                tr.appendChild(th);
                
                // Colunas para cada dia
                for (let dia = 1; dia <= 5; dia++) {
                    const td = document.createElement('td');
                    const key = `${dia}_${aula}`;
                    
                    if (agendamentos[key]) {
                        td.className = 'agendado';
                        td.innerHTML = `
                            <strong>${agendamentos[key].disciplina}</strong><br>
                            Prof. ${agendamentos[key].professor}<br>
                            ${agendamentos[key].turma}
                        `;
                    } else {
                        td.className = 'disponivel';
                        td.textContent = 'Disponível';
                        td.addEventListener('click', function() {
                            abrirModalAgendamento(dia, aula);
                        });
                    }
                    
                    tr.appendChild(td);
                }
                
                tbody.appendChild(tr);
            }
            
            // Configurar o botão de agendamento
            document.getElementById('agendar').addEventListener('click', agendarViaFormulario);
            
            // Configurar o modal
            const modal = document.getElementById('confirmModal');
            const span = document.getElementsByClassName('close')[0];
            
            span.onclick = function() {
                modal.style.display = "none";
            }
            
            document.getElementById('cancelBtn').onclick = function() {
                modal.style.display = "none";
            }
            
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
        
        function abrirModalAgendamento(dia, aula) {
            const modal = document.getElementById('confirmModal');
            const modalText = document.getElementById('modalText');
            
            modalText.innerHTML = `Você deseja agendar a ${aula}ª aula de ${getDiaSemana(dia)}?<br><br>
                                   Preencha os dados abaixo:<br>
                                   <div class="form-group">
                                       <label for="modalProfessor">Professor:</label>
                                       <input type="text" id="modalProfessor" required>
                                   </div>
                                   <div class="form-group">
                                       <label for="modalDisciplina">Disciplina:</label>
                                       <input type="text" id="modalDisciplina" required>
                                   </div>
                                   <div class="form-group">
                                       <label for="modalTurma">Turma:</label>
                                       <input type="text" id="modalTurma" required>
                                   </div>`;
            
            document.getElementById('confirmBtn').onclick = function() {
                const professor = document.getElementById('modalProfessor').value;
                const disciplina = document.getElementById('modalDisciplina').value;
                const turma = document.getElementById('modalTurma').value;
                
                if (professor && disciplina && turma) {
                    agendarAula(dia, aula, professor, disciplina, turma);
                    modal.style.display = "none";
                } else {
                    alert("Por favor, preencha todos os campos!");
                }
            };
            
            modal.style.display = "block";
        }
        
        function agendarViaFormulario() {
            const dia = document.getElementById('dia').value;
            const aula = document.getElementById('aula').value;
            const professor = document.getElementById('professor').value;
            const disciplina = document.getElementById('disciplina').value;
            const turma = document.getElementById('turma').value;
            
            if (!dia || !aula || !professor || !disciplina || !turma) {
                alert("Por favor, preencha todos os campos!");
                return;
            }
            
            agendarAula(dia, aula, professor, disciplina, turma);
            
            // Limpar formulário
            document.getElementById('professor').value = '';
            document.getElementById('disciplina').value = '';
            document.getElementById('turma').value = '';
            document.getElementById('dia').value = '';
            document.getElementById('aula').value = '';
        }
        
        function agendarAula(dia, aula, professor, disciplina, turma) {
            const key = `${dia}_${aula}`;
            
            // Verificar se já está agendado
            if (agendamentos[key]) {
                alert("Este horário já está agendado!");
                return;
            }
            
            // Adicionar ao objeto de agendamentos
            agendamentos[key] = {
                professor: professor,
                disciplina: disciplina,
                turma: turma
            };
            
            // Atualizar a tabela
            const tbody = document.querySelector('#grade tbody');
            const tr = tbody.children[aula - 1];
            const td = tr.children[dia];
            
            td.className = 'agendado';
            td.innerHTML = `
                <strong>${disciplina}</strong><br>
                Prof. ${professor}<br>
                ${turma}
            `;
            
            // Remover o event listener de clique
            td.replaceWith(td.cloneNode(true));
            
            alert(`Aula agendada com sucesso para ${getDiaSemana(dia)}, ${aula}ª aula!`);
        }
        
        function getDiaSemana(numero) {
            const dias = ['', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira'];
            return dias[numero];
        }
    </script>
</body>
</html>