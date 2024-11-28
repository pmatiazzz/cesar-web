<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', { packages: ['corechart'] });
            google.charts.setOnLoadCallback(() => {});

            var chart;

            function drawChart() {
                fetch('dadosAvaliacao.php')
                    .then(response => response.json())
                    .then(data => {
                        var chartData = google.visualization.arrayToDataTable(data);

                        var options = {
                            title: 'Distribuição de Avaliações por Estrelas',
                            legend: { position: 'bottom' },
                            chartArea: { width: '100%', height: '100%' }
                        };

                        document.getElementById('popup').style.display = 'block';
                        document.getElementById('overlay').style.display = 'block';

                        setTimeout(function() {
                            chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                            chart.draw(chartData, options);
                        }, 200); 
                    })
                    .catch(error => {
                        console.error('Erro ao carregar os dados:', error);
                    });
            }

            function closePopup() {
                document.getElementById('popup').style.display = 'none';
                document.getElementById('overlay').style.display = 'none';
            }
        </script>
        <style>
            #popup {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%); 
                width: 80vw; 
                height: 70vh; 
                background: white;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
                z-index: 1000;
                border-radius: 8px;
                padding: 20px;
                overflow: hidden;
            }

            #overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .close-btn {
                position: absolute;
                top: 10px;
                right: 10px;
                background: transparent;
                color: black;
                border: none;
                font-size: 24px;
                font-weight: bold; 
                cursor: pointer;
                z-index: 1010; 
            }

            #chart_div {
                width: 100%;
                height: 100%;
            }
            
            .button-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh; /* Isso faz com que os botões fiquem centralizados verticalmente na página */
            }

            .button-container button {
                margin: 10px;
                padding: 10px 0; /* A altura do botão será determinada pelo padding vertical */
                width: 250px; /* Definindo uma largura fixa para todos os botões */
                font-size: 16px;
                cursor: pointer;
                text-align: center; /* Alinha o texto ao centro */
            }
            
            .title {
                text-align: center;
                font-size: 48px;
                font-weight: bold;
                color: #333;
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <div class="title">
            Relatórios e Gráficos UniBooks
        </div>
        
        <div class="button-container">
            <a href="pdfObras.php" target="blank"><button>Relatorio Livros Catalogados</button></a>
            <a href="pdfLeitUsuarios.php" target="blank"><button>Relatorio Leitura por usuario</button></a>
            <a href="pdfComentUsuarios.php" target="blank"><button>Relatorio Comentário dos usuarios</button></a>
            <a href="pdfListAvaliacoes.php" target="blank"><button>Relatorio Lista de avaliações</button></a>
        
            <button onclick="drawChart()">Grafico de notas</button>
        </div>

        <div id="overlay" onclick="closePopup()"></div>
        <div id="popup">
            <button class="close-btn" onclick="closePopup()">X</button>
            <div id="chart_div"></div>
        </div>
    </body>
</html>